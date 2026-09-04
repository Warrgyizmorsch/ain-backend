<?php

namespace App\Services;

use App\Models\EmailMessage;
use App\Models\EmailThreadLabel;
use App\Models\Leads;
use App\Models\User;
use App\Models\WhatsappChatContactLabel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LabelSyncService
{
    /**
     * Sync labels from WhatsApp phone to associated email threads.
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
                foreach ($labelIds as $lId) {
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
            foreach ($labelIds as $lId) {
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

        // Never treat our own configured system accounts as customer email
        $configuredSystemEmails = \App\Models\EmailConfiguration::pluck('email_address')
            ->map(fn($e) => strtolower(trim($e)))
            ->filter()
            ->all();

        if ($cleanEmail && in_array($cleanEmail, $configuredSystemEmails)) {
            $cleanEmail = null;
        }

        // 1. Update email_thread_labels for this thread
        if (!empty($threadId)) {
            EmailThreadLabel::where('thread_id', $threadId)->delete();
            foreach ($labelIds as $lId) {
                EmailThreadLabel::create([
                    'thread_id' => $threadId,
                    'email' => !empty($cleanEmail) ? $cleanEmail : null,
                    'label_id' => (int) $lId,
                    'assigned_by' => $userId,
                ]);
            }
        } elseif (!empty($cleanEmail)) {
            EmailThreadLabel::where('email', $cleanEmail)->whereNull('thread_id')->delete();
            foreach ($labelIds as $lId) {
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

        // 3. Mirror labels to WhatsApp Contact Labels if associated phone found
        if (!empty($uniquePhones)) {
            foreach ($uniquePhones as $phone) {
                WhatsappChatContactLabel::query()->where('phone', $phone)->delete();
                foreach ($labelIds as $lId) {
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
            $emailLabelIds = EmailThreadLabel::where('email', $cleanEmail)->pluck('label_id')->unique()->all();
        }

        // 2. Check if this phone already has labels in WhatsappChatContactLabel
        $phoneLabelIds = [];
        if (!empty($variants)) {
            $phoneLabelIds = WhatsappChatContactLabel::whereIn('phone', $variants)->pluck('label_id')->unique()->all();
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
