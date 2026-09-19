<?php

namespace App\Services;

use App\Models\EmailMessage;
use App\Models\EmailThreadLabel;
use App\Models\Leads;
use App\Models\User;
use App\Models\WhatsappChatContactLabel;
use App\Models\WhatsappChatLabel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LabelSyncService
{
    /**
     * Sync labels from WhatsApp phone to associated email threads.
     * Only labels marked with is_email = true will sync to Email.
     *
     * @param string $phone
     * @param array<int> $labelIds
     * @param int|null $userId
     * @return array<string> List of synced email addresses
     */
    public function syncWhatsAppToEmail(string $phone, array $labelIds, ?int $userId = null): array
    {
        $cleanPhone = preg_replace('/\D+/', '', $phone);
        $last10 = substr($cleanPhone, -10);

        // Filter labels eligible for Email channel (is_email = true)
        $emailEligibleLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_email', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        // Find associated emails from Leads & Users
        $emails = collect();

        if (!empty($cleanPhone)) {
            $leadEmails = Leads::query()
                ->where(function ($q) use ($cleanPhone, $last10) {
                    $q->where('mobile', $cleanPhone)
                      ->orWhere(DB::raw("CONCAT(COALESCE(countrycode, ''), mobile)"), $cleanPhone);
                    if (strlen($last10) === 10) {
                        $q->orWhere('mobile', 'like', "%{$last10}");
                    }
                })
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->pluck('email');

            $userEmails = User::query()
                ->where(function ($q) use ($cleanPhone, $last10) {
                    $q->where('mobile_no', $cleanPhone)
                      ->orWhere(DB::raw("CONCAT(COALESCE(countrycode, ''), mobile_no)"), $cleanPhone);
                    if (strlen($last10) === 10) {
                        $q->orWhere('mobile_no', 'like', "%{$last10}");
                    }
                })
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->pluck('email');

            $emails = $emails->merge($leadEmails)->merge($userEmails);
        }

        $configuredSystemEmails = \App\Models\EmailConfiguration::pluck('email_address')
            ->map(fn($e) => strtolower(trim($e)))
            ->filter()
            ->all();

        $uniqueEmails = $emails
            ->map(fn ($e) => EmailMessage::extractCleanEmail($e))
            ->filter()
            ->reject(fn($e) => in_array($e, $configuredSystemEmails))
            ->unique()
            ->values()
            ->all();

        foreach ($uniqueEmails as $email) {
            // Find all active threads for this customer email
            $threadIds = EmailMessage::query()
                ->where(function ($q) use ($email) {
                    $q->where('from_email', $email)
                      ->orWhere('to_email', 'like', "%{$email}%");
                })
                ->whereNotNull('thread_id')
                ->pluck('thread_id')
                ->unique()
                ->values()
                ->all();

            // Sync email_thread_labels for each thread
            foreach ($threadIds as $tId) {
                EmailThreadLabel::where('thread_id', $tId)->delete();
                foreach ($emailEligibleLabelIds as $lId) {
                    EmailThreadLabel::create([
                        'thread_id' => $tId,
                        'email' => $email,
                        'label_id' => (int) $lId,
                        'assigned_by' => $userId,
                    ]);
                }
            }

            // Also keep generic customer email level label records
            EmailThreadLabel::where('email', $email)->whereNull('thread_id')->delete();
            foreach ($emailEligibleLabelIds as $lId) {
                EmailThreadLabel::create([
                    'thread_id' => null,
                    'email' => $email,
                    'label_id' => (int) $lId,
                    'assigned_by' => $userId,
                ]);
            }
        }

        return $uniqueEmails;
    }

    /**
     * Sync labels from Email to associated WhatsApp phone numbers.
     * Only labels marked with is_whatsapp = true will sync to WhatsApp.
     *
     * @param string $email
     * @param string|null $threadId
     * @param array<int> $labelIds
     * @param int|null $userId
     * @return array<string> List of synced phone numbers
     */
    public function syncEmailToWhatsApp(string $email, ?string $threadId, array $labelIds, ?int $userId = null): array
    {
        $cleanEmail = EmailMessage::extractCleanEmail($email);

        // Filter labels eligible for Email channel & WhatsApp channel
        $emailEligibleLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_email', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $waEligibleLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_whatsapp', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        // Never treat our own configured system accounts as customer email
        $configuredSystemEmails = \App\Models\EmailConfiguration::pluck('email_address')
            ->map(fn($e) => strtolower(trim($e)))
            ->filter()
            ->all();

        if ($cleanEmail && in_array($cleanEmail, $configuredSystemEmails)) {
            $cleanEmail = null;
        }

        // 1. Update email_thread_labels for this thread with email eligible labels
        if (!empty($threadId)) {
            EmailThreadLabel::where('thread_id', $threadId)->delete();
            foreach ($emailEligibleLabelIds as $lId) {
                EmailThreadLabel::create([
                    'thread_id' => $threadId,
                    'email' => !empty($cleanEmail) ? $cleanEmail : null,
                    'label_id' => (int) $lId,
                    'assigned_by' => $userId,
                ]);
            }
        } elseif (!empty($cleanEmail)) {
            EmailThreadLabel::where('email', $cleanEmail)->whereNull('thread_id')->delete();
            foreach ($emailEligibleLabelIds as $lId) {
                EmailThreadLabel::create([
                    'thread_id' => null,
                    'email' => $cleanEmail,
                    'label_id' => (int) $lId,
                    'assigned_by' => $userId,
                ]);
            }
        }

        // 2. Find associated phone numbers from Leads and Users for the customer email
        $phones = collect();

        if (!empty($cleanEmail)) {
            $leads = Leads::query()->where('email', $cleanEmail)->get(['countrycode', 'mobile']);
            foreach ($leads as $lead) {
                $code = preg_replace('/\D+/', '', (string) $lead->countrycode);
                $mob = preg_replace('/\D+/', '', (string) $lead->mobile);
                if (!empty($mob)) {
                    $full = (!empty($code) && !str_starts_with($mob, $code)) ? ($code . $mob) : $mob;
                    $phones->push($full);
                }
            }

            $users = User::query()->where('email', $cleanEmail)->get(['countrycode', 'mobile_no']);
            foreach ($users as $u) {
                $code = preg_replace('/\D+/', '', (string) $u->countrycode);
                $mob = preg_replace('/\D+/', '', (string) $u->mobile_no);
                if (!empty($mob)) {
                    $full = (!empty($code) && !str_starts_with($mob, $code)) ? ($code . $mob) : $mob;
                    $phones->push($full);
                }
            }
        }

        $uniquePhones = $phones->filter()->unique()->values()->all();

        // 3. Mirror WA eligible labels to WhatsApp Contact Labels if associated phone found
        if (!empty($uniquePhones) && !empty($waEligibleLabelIds)) {
            foreach ($uniquePhones as $phone) {
                WhatsappChatContactLabel::query()->where('phone', $phone)->delete();
                foreach ($waEligibleLabelIds as $lId) {
                    WhatsappChatContactLabel::query()->create([
                        'phone' => $phone,
                        'label_id' => (int) $lId,
                        'assigned_by' => $userId,
                    ]);
                }
            }
        }

        return $uniquePhones;
    }

    /**
     * Full cross-channel sync for a known User (by user_id).
     *
     * When a label is assigned via the CRM / Order view, this method fans out
     * the selected labels to EVERY phone number and EVERY email address that
     * belongs to the user (pulling from both the `users` table and associated
     * `leads` records).
     *
     * Rules:
     *  - Labels with `is_whatsapp = true` are written to `whatsapp_chat_contact_labels`.
     *  - Labels with `is_email    = true` are written to `email_thread_labels`.
     *
     * @param int        $userId
     * @param array<int> $labelIds   All selected label IDs (before channel filtering)
     * @param int|null   $assignedBy Auth user performing the save
     */
    public function syncUserLabelsAcrossAllChannels(int $userId, array $labelIds, ?int $assignedBy = null): void
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        // ── 1. Resolve channel-eligible label sets ───────────────────────────
        $waLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_whatsapp', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $emailLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_email', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        // ── 2. Collect ALL phone variants for this user ──────────────────────
        $allPhoneVariants = collect();

        $addPhone = function (?string $raw, ?string $cc) use (&$allPhoneVariants) {
            $clean = preg_replace('/\D+/', '', (string) $raw);
            if (empty($clean)) return;
            $code  = preg_replace('/\D+/', '', (string) $cc);
            $full  = (!empty($code) && !str_starts_with($clean, $code)) ? ($code . $clean) : $clean;
            $last10 = substr($clean, -10);
            foreach (array_filter(array_unique([$clean, $full, '+' . $full, $last10])) as $v) {
                $allPhoneVariants->push($v);
            }
        };

        // User's own phone
        $addPhone($user->mobile_no, $user->countrycode ?? '');

        // Phones from linked Leads
        $leads = Leads::where('email', $user->email)
            ->whereNotNull('mobile')->where('mobile', '!=', '')
            ->get(['mobile', 'countrycode']);
        foreach ($leads as $lead) {
            $addPhone($lead->mobile, $lead->countrycode ?? '');
        }

        $uniquePhones = $allPhoneVariants->filter()->unique()->values()->all();

        // ── 3. Collect ALL email addresses for this user ─────────────────────
        $allEmails = collect();

        if (!empty($user->email)) {
            $allEmails->push(strtolower(trim($user->email)));
        }

        // Emails from linked Leads (by phone)
        if (!empty($uniquePhones)) {
            $leadEmailsByPhone = Leads::whereIn('mobile', $uniquePhones)
                ->whereNotNull('email')->where('email', '!=', '')
                ->pluck('email');
            foreach ($leadEmailsByPhone as $e) {
                $allEmails->push(strtolower(trim($e)));
            }
        }

        // Never treat configured system accounts as customer emails
        $systemEmails = \App\Models\EmailConfiguration::pluck('email_address')
            ->map(fn($e) => strtolower(trim($e)))->filter()->all();

        $uniqueEmails = $allEmails
            ->filter()
            ->reject(fn($e) => in_array($e, $systemEmails))
            ->unique()
            ->values()
            ->all();

        // ── 4. Fan-out: WhatsApp ─────────────────────────────────────────────
        if (!empty($uniquePhones)) {
            // Wipe all existing label rows for every variant before re-inserting
            WhatsappChatContactLabel::whereIn('phone', $uniquePhones)->delete();

            // Use the canonical phone (from user record or first variant)
            $canonicalPhone = !empty($user->mobile_no)
                ? preg_replace('/\D+/', '', (string) $user->mobile_no)
                : ($uniquePhones[0] ?? null);

            if ($canonicalPhone) {
                // Rebuild phone with country-code if available
                $cc = preg_replace('/\D+/', '', (string) ($user->countrycode ?? ''));
                if (!empty($cc) && !str_starts_with($canonicalPhone, $cc)) {
                    $canonicalPhone = $cc . $canonicalPhone;
                }

                $now = now();
                $waInserts = [];
                foreach ($waLabelIds as $lId) {
                    $waInserts[] = [
                        'phone'       => $canonicalPhone,
                        'label_id'    => (int) $lId,
                        'assigned_by' => $assignedBy,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ];
                }
                if (!empty($waInserts)) {
                    WhatsappChatContactLabel::insert($waInserts);
                }
            }
        }

        // ── 5. Fan-out: Email ────────────────────────────────────────────────
        if (!empty($uniqueEmails)) {
            $now = now();
            foreach ($uniqueEmails as $email) {
                $emailInserts = [];

                // Fetch all thread IDs involving this customer email
                $threadIds = EmailMessage::where(function ($q) use ($email) {
                    $q->where('from_email', $email)
                      ->orWhere('to_email', 'like', "%{$email}%");
                })->whereNotNull('thread_id')
                  ->pluck('thread_id')
                  ->unique()
                  ->values()
                  ->all();

                if (!empty($threadIds)) {
                    EmailThreadLabel::whereIn('thread_id', $threadIds)->delete();
                    foreach ($threadIds as $tId) {
                        foreach ($emailLabelIds as $lId) {
                            $emailInserts[] = [
                                'thread_id'   => $tId,
                                'email'       => $email,
                                'label_id'    => (int) $lId,
                                'assigned_by' => $assignedBy,
                                'created_at'  => $now,
                                'updated_at'  => $now,
                            ];
                        }
                    }
                }

                // Also maintain a thread-less email-level record for future thread matching
                EmailThreadLabel::where('email', $email)->whereNull('thread_id')->delete();
                foreach ($emailLabelIds as $lId) {
                    $emailInserts[] = [
                        'thread_id'   => null,
                        'email'       => $email,
                        'label_id'    => (int) $lId,
                        'assigned_by' => $assignedBy,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ];
                }

                if (!empty($emailInserts)) {
                    EmailThreadLabel::insert($emailInserts);
                }
            }
        }

        Log::info("LabelSync: user {$userId} → WA phones=" . implode(',', $uniquePhones)
            . " | emails=" . implode(',', $uniqueEmails)
            . " | waLabels=" . implode(',', $waLabelIds)
            . " | emailLabels=" . implode(',', $emailLabelIds));
    }

    /**
     * Cross-sync existing labels when a User or Lead is created or updated.
     * If email has existing labels, sync to the phone.
     * If phone has existing labels, sync to the email.
     */
    public function syncOnContactCreatedOrUpdated(?string $email, ?string $countryCode, ?string $mobile, ?int $userId = null): void
    {
        $cleanEmail = strtolower(trim((string) $email));
        $cleanMobile = preg_replace('/\D+/', '', (string) $mobile);
        $cleanCode = preg_replace('/\D+/', '', (string) $countryCode);

        if (empty($cleanEmail) && empty($cleanMobile)) {
            return;
        }

        $fullPhone = (!empty($cleanCode) && !str_starts_with($cleanMobile, $cleanCode)) ? ($cleanCode . $cleanMobile) : $cleanMobile;
        $variants = array_values(array_filter(array_unique([$cleanMobile, $fullPhone, substr($cleanMobile, -10)])));

        // 1. Check if this email already has labels in EmailThreadLabel
        $emailLabelIds = [];
        if (!empty($cleanEmail)) {
            $rawEmailLabelIds = EmailThreadLabel::where('email', $cleanEmail)->pluck('label_id')->unique()->all();
            $emailLabelIds = WhatsappChatLabel::whereIn('id', $rawEmailLabelIds)
                ->where('is_whatsapp', true)
                ->pluck('id')
                ->all();
        }

        // 2. Check if this phone already has labels in WhatsappChatContactLabel
        $phoneLabelIds = [];
        if (!empty($variants)) {
            $rawPhoneLabelIds = WhatsappChatContactLabel::whereIn('phone', $variants)->pluck('label_id')->unique()->all();
            $phoneLabelIds = WhatsappChatLabel::whereIn('id', $rawPhoneLabelIds)
                ->where('is_email', true)
                ->pluck('id')
                ->all();
        }

        // 3. If email has labels but phone does not -> mirror email labels to WhatsApp
        if (!empty($emailLabelIds) && empty($phoneLabelIds) && !empty($variants)) {
            foreach ($variants as $phone) {
                foreach ($emailLabelIds as $lId) {
                    WhatsappChatContactLabel::firstOrCreate([
                        'phone' => $phone,
                        'label_id' => (int) $lId,
                    ], [
                        'assigned_by' => $userId,
                    ]);
                }
            }
        }
        // 4. If phone has labels but email does not -> mirror WhatsApp labels to Email
        elseif (!empty($phoneLabelIds) && empty($emailLabelIds) && !empty($cleanEmail)) {
            $threadIds = EmailMessage::where(function ($q) use ($cleanEmail) {
                $q->where('from_email', $cleanEmail)->orWhere('to_email', 'like', "%{$cleanEmail}%");
            })->whereNotNull('thread_id')->where('thread_id', '!=', '')->pluck('thread_id')->unique()->values()->all();

            if (!empty($threadIds)) {
                foreach ($threadIds as $tId) {
                    foreach ($phoneLabelIds as $lId) {
                        EmailThreadLabel::firstOrCreate([
                            'thread_id' => $tId,
                            'label_id' => (int) $lId,
                        ], [
                            'email' => $cleanEmail,
                            'assigned_by' => $userId,
                        ]);
                    }
                }
            } else {
                foreach ($phoneLabelIds as $lId) {
                    EmailThreadLabel::firstOrCreate([
                        'email' => $cleanEmail,
                        'label_id' => (int) $lId,
                    ], [
                        'thread_id' => null,
                        'assigned_by' => $userId,
                    ]);
                }
            }
        }
        // 5. If both have labels -> merge both sets and sync across both
        elseif (!empty($emailLabelIds) && !empty($phoneLabelIds)) {
            $merged = array_values(array_unique(array_merge($emailLabelIds, $phoneLabelIds)));
            if (!empty($variants)) {
                foreach ($variants as $phone) {
                    foreach ($merged as $lId) {
                        WhatsappChatContactLabel::firstOrCreate([
                            'phone' => $phone,
                            'label_id' => (int) $lId,
                        ], [
                            'assigned_by' => $userId,
                        ]);
                    }
                }
            }
            if (!empty($cleanEmail)) {
                foreach ($merged as $lId) {
                    EmailThreadLabel::firstOrCreate([
                        'email' => $cleanEmail,
                        'label_id' => (int) $lId,
                    ], [
                        'thread_id' => null,
                        'assigned_by' => $userId,
                    ]);
                }
            }
        }
    }
}
