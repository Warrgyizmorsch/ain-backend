<?php

namespace App\Services;

use App\Models\CrmUserLabel;
use App\Models\EmailConfiguration;
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
     * Resolve a User instance from user_id, phone number, or email address.
     */
    public function resolveUser(?int $userId = null, ?string $phone = null, ?string $email = null): ?User
    {
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                return $user;
            }
        }

        if (!empty($phone)) {
            $cleanPhone = preg_replace('/\D+/', '', $phone);
            $last10 = strlen($cleanPhone) >= 10 ? substr($cleanPhone, -10) : $cleanPhone;

            $user = User::query()
                ->where(function ($q) use ($cleanPhone, $last10) {
                    $q->where('mobile_no', $cleanPhone)
                      ->orWhere(DB::raw("CONCAT(COALESCE(countrycode, ''), mobile_no)"), $cleanPhone);
                    if (strlen($last10) === 10) {
                        $q->orWhere('mobile_no', 'like', "%{$last10}");
                    }
                })
                ->first();

            if ($user) {
                return $user;
            }
        }

        if (!empty($email)) {
            $cleanEmail = EmailMessage::extractCleanEmail($email);
            if ($cleanEmail) {
                $user = User::where('email', $cleanEmail)->first();
                if ($user) {
                    return $user;
                }
            }
        }

        return null;
    }

    /**
     * Sync labels from WhatsApp contact phone across channels.
     * Strict rules:
     *  - If contact is registered User: full sync across CRM, WhatsApp & Email.
     *  - is_whatsapp: applied to WhatsApp contact.
     *  - is_email: applied to associated Email threads.
     *  - is_crm: applied to CRM User labels.
     *
     * @param string $phone
     * @param array<int> $labelIds
     * @param int|null $userId
     * @return array<string> List of synced email addresses
     */
    public function syncWhatsAppToEmail(string $phone, array $labelIds, ?int $userId = null): array
    {
        $cleanPhone = preg_replace('/\D+/', '', $phone);
        $user = $this->resolveUser($userId, $cleanPhone);

        if ($user) {
            $this->syncUserLabelsAcrossAllChannels($user->id, $labelIds, $userId);
            return !empty($user->email) ? [$user->email] : [];
        }

        // Unregistered contact sync
        $waEligibleLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_whatsapp', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $emailEligibleLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_email', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $crmEligibleLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_crm', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        // 1. WhatsApp Contact Labels
        if (!empty($cleanPhone)) {
            $variants = $this->getPhoneVariants($cleanPhone);
            WhatsappChatContactLabel::whereIn('phone', $variants)->delete();
            foreach ($waEligibleLabelIds as $lId) {
                WhatsappChatContactLabel::create([
                    'phone' => $cleanPhone,
                    'label_id' => (int) $lId,
                    'assigned_by' => $userId,
                ]);
            }

            // 2. CRM User Labels (stored by phone for future orders/registration)
            CrmUserLabel::where(function($q) use ($variants) {
                $q->whereIn('phone', $variants);
            })->whereNull('user_id')->delete();

            foreach ($crmEligibleLabelIds as $lId) {
                CrmUserLabel::create([
                    'user_id' => null,
                    'phone' => $cleanPhone,
                    'email' => null,
                    'label_id' => (int) $lId,
                    'assigned_by' => $userId,
                ]);
            }
        }

        // 3. Find associated emails from Leads
        $emails = collect();
        if (!empty($cleanPhone)) {
            $last10 = substr($cleanPhone, -10);
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

            $emails = $emails->merge($leadEmails);
        }

        $configuredSystemEmails = EmailConfiguration::pluck('email_address')
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
     * Sync labels from Email to associated WhatsApp phone numbers and CRM.
     * Strict rules:
     *  - If email belongs to registered User: full sync across CRM, WhatsApp & Email.
     *  - is_email: applied to Email thread / customer email.
     *  - is_whatsapp: applied to WhatsApp contact if phone found.
     *  - is_crm: applied to CRM User labels.
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
        $user = $this->resolveUser($userId, null, $cleanEmail);

        if ($user) {
            $this->syncUserLabelsAcrossAllChannels($user->id, $labelIds, $userId);
            return !empty($user->mobile_no) ? [$user->mobile_no] : [];
        }

        // Unregistered customer sync
        $targetConfig = null;
        if (!empty($threadId)) {
            $msg = EmailMessage::where('thread_id', $threadId)->first(['email_configuration_id']);
            if ($msg && $msg->email_configuration_id) {
                $targetConfig = EmailConfiguration::find($msg->email_configuration_id);
            }
        }
        $isWriterThread = $targetConfig && ((int)$targetConfig->id === 1 || stripos($targetConfig->name, 'writer') !== false);
        $isClientThread = $targetConfig && ((int)$targetConfig->id === 2 || stripos($targetConfig->name, 'client') !== false);

        if ($isWriterThread) {
            $emailEligibleLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
                ->where('is_writer_email', true)
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->all();
        } elseif ($isClientThread) {
            $emailEligibleLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
                ->where('is_client_email', true)
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->all();
        } else {
            $emailEligibleLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
                ->where(function ($q) {
                    $q->where('is_client_email', true)->orWhere('is_writer_email', true)->orWhere('is_email', true);
                })
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->all();
        }

        $waEligibleLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_whatsapp', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $crmEligibleLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_crm', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $configuredSystemEmails = EmailConfiguration::pluck('email_address')
            ->map(fn($e) => strtolower(trim($e)))
            ->filter()
            ->all();

        if ($cleanEmail && in_array($cleanEmail, $configuredSystemEmails)) {
            $cleanEmail = null;
        }

        // 1. Update email_thread_labels for this thread / email
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

        // 2. CRM user labels by email
        if (!empty($cleanEmail)) {
            CrmUserLabel::where('email', $cleanEmail)->whereNull('user_id')->delete();
            foreach ($crmEligibleLabelIds as $lId) {
                CrmUserLabel::create([
                    'user_id' => null,
                    'phone' => null,
                    'email' => $cleanEmail,
                    'label_id' => (int) $lId,
                    'assigned_by' => $userId,
                ]);
            }
        }

        // 3. Find associated phone numbers from Leads
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
        }

        $uniquePhones = $phones->filter()->unique()->values()->all();

        // 4. Mirror WA eligible labels to WhatsApp Contact Labels if associated phone found
        if (!empty($uniquePhones) && !empty($waEligibleLabelIds)) {
            foreach ($uniquePhones as $phone) {
                $cleanP = preg_replace('/\D+/', '', $phone);
                $variants = $this->getPhoneVariants($cleanP);
                WhatsappChatContactLabel::query()->whereIn('phone', $variants)->delete();
                foreach ($waEligibleLabelIds as $lId) {
                    WhatsappChatContactLabel::query()->create([
                        'phone' => $cleanP,
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
     * Fans out the selected labels to EVERY channel according to its master flag:
     *  - Labels with is_crm      = true  => written to crm_user_labels (applies to all user orders & profile).
     *  - Labels with is_whatsapp = true  => written to whatsapp_chat_contact_labels.
     *  - Labels with is_email    = true  => written to email_thread_labels.
     *
     * If a flag is false (0), the label is explicitly omitted/removed from that channel.
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
        $crmLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_crm', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $waLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_whatsapp', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $writerLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_writer_email', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $clientLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where('is_client_email', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $emailLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
            ->where(function ($q) {
                $q->where('is_client_email', true)->orWhere('is_writer_email', true)->orWhere('is_email', true);
            })
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
            $last10 = strlen($clean) >= 10 ? substr($clean, -10) : $clean;
            foreach (array_filter(array_unique([$clean, $full, '+' . $full, $last10])) as $v) {
                $allPhoneVariants->push($v);
            }
        };

        // User's own primary & secondary phone
        $addPhone($user->mobile_no, $user->countrycode ?? '');
        if (!empty($user->mobile_no2)) {
            $addPhone($user->mobile_no2, $user->countrycode ?? '');
        }

        // Phones from linked Leads
        if (!empty($user->email)) {
            $leads = Leads::where('email', $user->email)
                ->whereNotNull('mobile')->where('mobile', '!=', '')
                ->get(['mobile', 'countrycode']);
            foreach ($leads as $lead) {
                $addPhone($lead->mobile, $lead->countrycode ?? '');
            }
        }

        $uniquePhones = $allPhoneVariants->filter()->unique()->values()->all();

        // ── 3. Collect ALL email addresses for this user ─────────────────────
        $allEmails = collect();

        if (!empty($user->email)) {
            $cleanUe = EmailMessage::extractCleanEmail($user->email);
            if ($cleanUe) {
                $allEmails->push($cleanUe);
            }
        }

        // Emails from linked Leads (by phone)
        if (!empty($uniquePhones)) {
            $leadEmailsByPhone = Leads::whereIn('mobile', $uniquePhones)
                ->whereNotNull('email')->where('email', '!=', '')
                ->pluck('email');
            foreach ($leadEmailsByPhone as $e) {
                $cleanLe = EmailMessage::extractCleanEmail($e);
                if ($cleanLe) {
                    $allEmails->push($cleanLe);
                }
            }
        }

        // Never treat configured system accounts as customer emails
        $systemEmails = EmailConfiguration::pluck('email_address')
            ->map(fn($e) => strtolower(trim($e)))->filter()->all();

        $uniqueEmails = $allEmails
            ->filter()
            ->reject(fn($e) => in_array($e, $systemEmails))
            ->unique()
            ->values()
            ->all();

        // ── 4. Fan-out: CRM Labels (crm_user_labels) ────────────────────────
        CrmUserLabel::where('user_id', $user->id)->delete();
        if (!empty($uniquePhones) || !empty($uniqueEmails)) {
            CrmUserLabel::where(function ($q) use ($uniquePhones, $uniqueEmails) {
                if (!empty($uniquePhones)) {
                    $q->whereIn('phone', $uniquePhones);
                }
                if (!empty($uniqueEmails)) {
                    $q->orWhereIn('email', $uniqueEmails);
                }
            })->whereNull('user_id')->delete();
        }

        $now = now();
        $crmInserts = [];
        foreach ($crmLabelIds as $lId) {
            $crmInserts[] = [
                'user_id'     => $user->id,
                'phone'       => $user->mobile_no ?: ($uniquePhones[0] ?? null),
                'email'       => $user->email ?: ($uniqueEmails[0] ?? null),
                'label_id'    => (int) $lId,
                'assigned_by' => $assignedBy,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }
        if (!empty($crmInserts)) {
            CrmUserLabel::insert($crmInserts);
        }

        // ── 5. Fan-out: WhatsApp (whatsapp_chat_contact_labels) ──────────────
        if (!empty($uniquePhones)) {
            WhatsappChatContactLabel::whereIn('phone', $uniquePhones)->delete();

            $canonicalPhone = !empty($user->mobile_no)
                ? preg_replace('/\D+/', '', (string) $user->mobile_no)
                : ($uniquePhones[0] ?? null);

            if ($canonicalPhone) {
                $cc = preg_replace('/\D+/', '', (string) ($user->countrycode ?? ''));
                if (!empty($cc) && !str_starts_with($canonicalPhone, $cc)) {
                    $canonicalPhone = $cc . $canonicalPhone;
                }

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

        // ── 6. Fan-out: Email (email_thread_labels) ──────────────────────────
        if (!empty($uniqueEmails)) {
            foreach ($uniqueEmails as $email) {
                $threadIds = EmailMessage::where(function ($q) use ($email) {
                    $q->where('from_email', $email)
                      ->orWhere('to_email', 'like', "%{$email}%");
                })->whereNotNull('thread_id')
                  ->pluck('thread_id')
                  ->unique()
                  ->values()
                  ->all();

                if (!empty($threadIds)) {
                    $threadConfigMap = EmailMessage::whereIn('thread_id', $threadIds)
                        ->whereNotNull('email_configuration_id')
                        ->pluck('email_configuration_id', 'thread_id')
                        ->all();

                    EmailThreadLabel::whereIn('thread_id', $threadIds)->delete();
                    $threadInserts = [];
                    foreach ($threadIds as $tId) {
                        $cfgId = $threadConfigMap[$tId] ?? null;
                        if ((int)$cfgId === 1) {
                            $targetLabelIds = $writerLabelIds;
                        } elseif ((int)$cfgId === 2) {
                            $targetLabelIds = $clientLabelIds;
                        } else {
                            $targetLabelIds = $emailLabelIds;
                        }

                        foreach ($targetLabelIds as $lId) {
                            $threadInserts[] = [
                                'thread_id'   => $tId,
                                'email'       => $email,
                                'label_id'    => (int) $lId,
                                'assigned_by' => $assignedBy,
                                'created_at'  => $now,
                                'updated_at'  => $now,
                            ];
                        }
                    }
                    if (!empty($threadInserts)) {
                        EmailThreadLabel::insert($threadInserts);
                    }
                }

                EmailThreadLabel::where('email', $email)->whereNull('thread_id')->delete();
                $emailInserts = [];
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

        Log::info("LabelSync: user {$userId} → CRM=" . implode(',', $crmLabelIds)
            . " | WA=" . implode(',', $waLabelIds)
            . " | Email=" . implode(',', $emailLabelIds));
    }

    /**
     * Helper to get phone variants for matching
     */
    protected function getPhoneVariants(string $phone): array
    {
        $clean = preg_replace('/\D+/', '', $phone);
        if (empty($clean)) return [];
        $last10 = strlen($clean) >= 10 ? substr($clean, -10) : $clean;
        return array_values(array_filter(array_unique([
            $phone,
            $clean,
            '+' . $clean,
            $last10,
            '+91' . $last10,
            '91' . $last10,
            '+44' . $last10,
            '44' . $last10,
        ])));
    }

    /**
     * Cross-sync existing labels when a User or Lead is created or updated.
     */
    public function syncOnContactCreatedOrUpdated(?string $email, ?string $countryCode, ?string $mobile, ?int $userId = null): void
    {
        $cleanEmail = strtolower(trim((string) $email));
        $cleanMobile = preg_replace('/\D+/', '', (string) $mobile);
        $user = $this->resolveUser($userId, $cleanMobile, $cleanEmail);

        $collectedLabelIds = collect();

        // 1. Check existing CRM user labels
        if ($user) {
            $crmExisting = CrmUserLabel::where('user_id', $user->id)->pluck('label_id');
            $collectedLabelIds = $collectedLabelIds->concat($crmExisting);
        }

        // 2. Check existing Email labels
        if (!empty($cleanEmail)) {
            $emailExisting = EmailThreadLabel::where('email', $cleanEmail)->pluck('label_id');
            $collectedLabelIds = $collectedLabelIds->concat($emailExisting);
        }

        // 3. Check existing WhatsApp contact labels
        if (!empty($cleanMobile)) {
            $variants = $this->getPhoneVariants($cleanMobile);
            $waExisting = WhatsappChatContactLabel::whereIn('phone', $variants)->pluck('label_id');
            $collectedLabelIds = $collectedLabelIds->concat($waExisting);
        }

        $allUniqueLabelIds = $collectedLabelIds->unique()->filter()->values()->all();
        if (empty($allUniqueLabelIds)) {
            return;
        }

        if ($user) {
            $this->syncUserLabelsAcrossAllChannels($user->id, $allUniqueLabelIds, $userId);
        } else {
            if (!empty($cleanMobile)) {
                $this->syncWhatsAppToEmail($cleanMobile, $allUniqueLabelIds, $userId);
            } elseif (!empty($cleanEmail)) {
                $this->syncEmailToWhatsApp($cleanEmail, null, $allUniqueLabelIds, $userId);
            }
        }
    }
}
