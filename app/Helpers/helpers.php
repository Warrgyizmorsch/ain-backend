<?php

use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('getUserRoleName')) {
    function getUserRoleName($role_id)
    {
        return match ($role_id) {
            1 => 'Admin',
            2 => 'User',
            3 => 'Feedback Team',
            4 => 'Marketing Team',
            5 => 'Project Team',
            6 => 'Writer Team Leader',
            7 => 'Writer Team',
            8 => 'Writer Admin',
            9 => 'Sub Admin',
            default => 'Unknown',
        };
    }
}

if (!function_exists('mask_phone_for_display')) {
    /**
     * Show full number only to admins (role_id 1). Everyone else sees
     * the mobile number in masked format (country code + last 4 digits only).
     */
    function mask_phone_for_display(?string $countryCode, ?string $mobile): string
    {
        if (Auth::check() && (int) Auth::user()->role_id === 1) {
            $mobileStr = trim((string) $mobile);
            if ($mobileStr === '') {
                return '';
            }
            $cleanCC = preg_replace('/\D+/', '', (string) $countryCode);
            if (!empty($cleanCC) && !str_starts_with(preg_replace('/\D+/', '', $mobileStr), $cleanCC)) {
                return '+' . $cleanCC . ' ' . $mobileStr;
            }
            return $mobileStr;
        }
        $cleanCC = preg_replace('/\D+/', '', (string) $countryCode);
        $ccPrefix = !empty($cleanCC) ? ('+' . $cleanCC) : '';
        return $ccPrefix . mask_mobile_only($countryCode, $mobile);
    }
}

if (!function_exists('mask_mobile_only')) {
    /**
     * Masks only the mobile number part (without country code) for form fields & display.
     * Shows only asterisks + last 4 digits (e.g. ******3210).
     */
    function mask_mobile_only(?string $countryCode, ?string $mobile): string
    {
        $mobileStr = trim((string) $mobile);
        if ($mobileStr === '') {
            return '';
        }

        // Show full unmasked number for Super Admin (role_id 1)
        if (Auth::check() && (int) Auth::user()->role_id === 1) {
            return $mobileStr;
        }

        $digits = preg_replace('/\D+/', '', $mobileStr);
        $cleanCC = preg_replace('/\D+/', '', (string) $countryCode);

        // If mobile starts with country code and is longer than country code + 4, strip country code prefix
        if (!empty($cleanCC) && strpos($digits, $cleanCC) === 0 && strlen($digits) > strlen($cleanCC) + 4) {
            $digits = substr($digits, strlen($cleanCC));
        }

        // If 11 digits starting with 0, strip leading 0
        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        $len = strlen($digits);
        if ($len <= 4) {
            return str_repeat('*', max(4, $len));
        }

        // Only asterisks + last 4 digits (no leading digits)
        return '******' . substr($digits, -4);
    }
}

if (!function_exists('mask_raw_phone')) {
    /**
     * Masks complete phone numbers with country code:
     * e.g. +44******3818 or ******3210 (country code + last 4 digits only)
     */
    function mask_raw_phone(?string $phone): string
    {
        $phoneStr = trim((string) $phone);
        if ($phoneStr === '') {
            return '';
        }

        // Show full unmasked phone for Super Admin (role_id 1)
        if (Auth::check() && (int) Auth::user()->role_id === 1) {
            return $phoneStr;
        }

        $prefix = '';
        $digits = preg_replace('/\D+/', '', $phoneStr);
        $trimmed = trim($phoneStr);
        if (str_starts_with($trimmed, '+')) {
            if (preg_match('/^(\+(?:1|44|91|61|971|86|33|49|81|65|60|64|\d{1,3}))/', $trimmed, $m)) {
                $prefix = $m[1];
                $cleanCC = preg_replace('/\D+/', '', $m[1]);
                if (str_starts_with($digits, $cleanCC)) {
                    $digits = substr($digits, strlen($cleanCC));
                }
            }
        } elseif ((str_starts_with($digits, '91') || str_starts_with($digits, '44')) && strlen($digits) > 10) {
            $prefix = '+' . substr($digits, 0, 2);
            $digits = substr($digits, 2);
        } elseif (strlen($digits) > 10) {
            $prefix = '+' . substr($digits, 0, strlen($digits) - 10);
            $digits = substr($digits, -10);
        }

        $len = strlen($digits);
        if ($len <= 4) {
            return $prefix . str_repeat('*', max(4, $len));
        }

        // Country code + asterisks + last 4 digits (no leading digits)
        return $prefix . '******' . substr($digits, -4);
    }
}

if (!function_exists('mask_email_for_display')) {
    /**
     * Show full email only to Super Admins (role_id 1). Everyone else sees
     * masked email e.g. a******h@gmail.com
     */
    function mask_email_for_display(?string $email): string
    {
        $email = trim((string) $email);

        if ($email === '') {
            return 'N/A';
        }

        if (Auth::check() && (int) Auth::user()->role_id === 1) {
            return $email;
        }

        $parts = explode('@', $email, 2);
        if (count($parts) !== 2) {
            $len = strlen($email);
            if ($len <= 4) {
                return str_repeat('*', $len);
            }
            return substr($email, 0, 1) . '******' . substr($email, -1);
        }

        $name = $parts[0];
        $domain = $parts[1];

        $len = strlen($name);
        if ($len <= 1) {
            $maskedName = $name . '******';
        } else {
            $maskedName = substr($name, 0, 1) . '******' . substr($name, -1);
        }

        return $maskedName . '@' . $domain;
    }
}

if (!function_exists('mask_email_contact')) {
    function mask_email_contact(?string $email): string
    {
        return mask_email_for_display($email);
    }
}

if (!function_exists('mask_chat_text')) {
    /**
     * Masks any 10-13 digit phone numbers and email addresses inside chat text.
     * Super Admin (role_id 1) sees full unmasked content.
     * All other users see masked phone (country code + last 4 digits) & email (a******h@gmail.com).
     */
    function mask_chat_text(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        if (Auth::check() && (int) Auth::user()->role_id === 1) {
            return $text;
        }

        // 1. Mask Email Addresses (e.g. a******h@gmail.com)
        $text = preg_replace_callback('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', function ($matches) {
            $email = $matches[0];
            return mask_email_for_display($email);
        }, $text);

        // 2. Mask 10-13 Digit Phone Numbers (country code + last 4 digits only, e.g. +91******3210 or ******3210)
        $text = preg_replace_callback('/(?:\+?\d[\d\s\-\(\)\.]{8,18}\d)/', function ($matches) {
            $raw = $matches[0];
            if (str_contains($raw, '*')) {
                return $raw;
            }
            $digits = preg_replace('/\D+/', '', $raw);
            $digitCount = strlen($digits);
            if ($digitCount >= 10 && $digitCount <= 13) {
                $prefix = '';
                $mobileDigits = $digits;
                $trimmed = trim($raw);
                if (str_starts_with($trimmed, '+')) {
                    if (preg_match('/^(\+(?:1|44|91|61|971|86|33|49|81|65|60|64|\d{1,3}))/', $trimmed, $m)) {
                        $prefix = $m[1];
                        $cleanCC = preg_replace('/\D+/', '', $m[1]);
                        if (str_starts_with($mobileDigits, $cleanCC)) {
                            $mobileDigits = substr($mobileDigits, strlen($cleanCC));
                        }
                    }
                } elseif ((str_starts_with($digits, '91') || str_starts_with($digits, '44')) && $digitCount > 10) {
                    $prefix = '+' . substr($digits, 0, 2);
                    $mobileDigits = substr($digits, 2);
                } elseif ($digitCount > 10) {
                    $prefix = '+' . substr($digits, 0, $digitCount - 10);
                    $mobileDigits = substr($digits, -10);
                }

                return $prefix . '******' . substr($mobileDigits, -4);
            }
            return $raw;
        }, $text);

        return $text;
    }
}

if (!function_exists('format_whatsapp_message_html')) {
    /**
     * Formats WhatsApp / Markdown text into rich HTML:
     * - Preserves line breaks & multiline paragraphs
     * - Blockquotes (> quote)
     * - Bold (*bold* and **bold**)
     * - Italic (_italic_)
     * - Strikethrough (~strike~)
     * - Code blocks (```code``` and `code`)
     * - Clickable links (https://...)
     */
    function format_whatsapp_message_html(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        // 1. Normalize line endings
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // 2. Code blocks (```...```)
        $text = preg_replace_callback('/```([\s\S]*?)```/', function ($m) {
            return '<pre class="wab-code-block"><code>' . $m[1] . '</code></pre>';
        }, $text);

        // 3. Inline code (`...`)
        $text = preg_replace('/`([^`\n]+)`/', '<code class="wab-inline-code">$1</code>', $text);

        // 4. Blockquotes: lines starting with &gt; or >
        $lines = explode("\n", $text);
        $inQuote = false;
        $quoteLines = [];
        $outputLines = [];

        foreach ($lines as $line) {
            if (preg_match('/^(?:&gt;|>)\s?(.*)$/', $line, $qm)) {
                $inQuote = true;
                $quoteLines[] = $qm[1];
            } else {
                if ($inQuote) {
                    $outputLines[] = '<div class="wab-quote-block">' . implode("\n", $quoteLines) . '</div>';
                    $quoteLines = [];
                    $inQuote = false;
                }
                $outputLines[] = $line;
            }
        }
        if ($inQuote) {
            $outputLines[] = '<div class="wab-quote-block">' . implode("\n", $quoteLines) . '</div>';
        }
        $text = implode("\n", $outputLines);

        // 5. Bold: support both **bold** (markdown/AI bots) and *bold* (standard WhatsApp)
        $text = preg_replace('/\*\*([^\*\n]+?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/(?<![\w*])\*(?!\s)([^\*\n]+?)(?<!\s)\*(?![\w*])/', '<strong>$1</strong>', $text);

        // 6. Italic: _italic_
        $text = preg_replace('/(?<![\w_])_(?!\s)([^_\n]+?)(?<!\s)_(?![\w_])/', '<em>$1</em>', $text);

        // 7. Strikethrough: ~strike~
        $text = preg_replace('/(?<![\w~])~(?!\s)([^~\n]+?)(?<!\s)~(?![\w~])/', '<del>$1</del>', $text);

        // 8. Links: https:// or http://
        $text = preg_replace_callback('/(?<!["\'>=])(https?:\/\/[^\s<]+[a-zA-Z0-9\/])/i', function ($m) {
            return '<a href="' . $m[1] . '" target="_blank" rel="noopener noreferrer" class="wab-msg-link">' . $m[1] . '</a>';
        }, $text);

        return $text;
    }
}

if (!function_exists('mask_chat_html')) {
    /**
     * Returns HTML for chat messages with masked spans for 1-click copy
     */
    function mask_chat_html(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        if (Auth::check() && (int) Auth::user()->role_id === 1) {
            return format_whatsapp_message_html(e($text));
        }

        $escaped = e($text);

        // 1. Mask Email with interactive copy span (e.g. a******h@gmail.com)
        $escaped = preg_replace_callback('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', function ($matches) {
            $email = $matches[0];
            $masked = mask_email_for_display($email);
            $safeMasked = e($masked);
            $safeReal = e($email);
            return '<span class="wab-masked-entity wab-masked-email" data-type="email" data-masked="' . $safeMasked . '" data-real="' . $safeReal . '" onclick="window.copyMaskedEntity && window.copyMaskedEntity(\'' . $safeMasked . '\', this, event)" title="Click to copy masked email">' . $safeMasked . '</span>';
        }, $escaped);

        // 2. Mask 10-13 Digit Phone Numbers with interactive copy span (country code + last 4 digits only)
        $escaped = preg_replace_callback('/(?:\+?\d[\d\s\-\(\)\.]{8,18}\d)/', function ($matches) {
            $raw = $matches[0];
            if (str_contains($raw, '*')) {
                return $raw;
            }
            $digits = preg_replace('/\D+/', '', $raw);
            $digitCount = strlen($digits);
            if ($digitCount >= 10 && $digitCount <= 13) {
                $prefix = '';
                $mobileDigits = $digits;
                $trimmed = trim($raw);
                if (str_starts_with($trimmed, '+')) {
                    if (preg_match('/^(\+(?:1|44|91|61|971|86|33|49|81|65|60|64|\d{1,3}))/', $trimmed, $m)) {
                        $prefix = $m[1];
                        $cleanCC = preg_replace('/\D+/', '', $m[1]);
                        if (str_starts_with($mobileDigits, $cleanCC)) {
                            $mobileDigits = substr($mobileDigits, strlen($cleanCC));
                        }
                    }
                } elseif ((str_starts_with($digits, '91') || str_starts_with($digits, '44')) && $digitCount > 10) {
                    $prefix = '+' . substr($digits, 0, 2);
                    $mobileDigits = substr($digits, 2);
                } elseif ($digitCount > 10) {
                    $prefix = '+' . substr($digits, 0, $digitCount - 10);
                    $mobileDigits = substr($digits, -10);
                }

                $masked = $prefix . '******' . substr($mobileDigits, -4);
                $safeMasked = e($masked);
                $safeReal = e($digits);
                return '<span class="wab-masked-entity wab-masked-phone" data-type="phone" data-masked="' . $safeMasked . '" data-real="' . $safeReal . '" onclick="window.copyMaskedEntity && window.copyMaskedEntity(\'' . $safeMasked . '\', this, event)" title="Click to copy masked phone">' . $safeMasked . '</span>';
            }
            return $raw;
        }, $escaped);

        return format_whatsapp_message_html($escaped);
    }
}


if (!function_exists('find_user_ids_by_search_term')) {
    /**
     * Finds matching user IDs whether the search term is a full phone number,
     * masked phone number (4474****2051), full email, masked email, or name.
     */
    function find_user_ids_by_search_term(?string $term): array
    {
        $term = trim((string) $term);
        if ($term === '') {
            return [];
        }

        $query = \App\Models\User::query();

        $hasAsterisk = strpos($term, '*') !== false;

        // 1. Masked phone number (e.g. 4474****2051, 77******9811)
        if ($hasAsterisk) {
            $rawMaskedPhone = preg_replace('/[^0-9*]/', '', $term);
            $maskedPhonePattern = str_replace('*', '_', $rawMaskedPhone);
            $withoutLeadingZeroPattern = ltrim($maskedPhonePattern, '0');

            if (!empty($maskedPhonePattern) && strpos($maskedPhonePattern, '_') !== false && preg_match('/\d/', $maskedPhonePattern)) {
                $query->where(function ($q) use ($maskedPhonePattern, $withoutLeadingZeroPattern) {
                    // Each mask character represents exactly one hidden digit. Do not
                    // wrap this in %, otherwise a visible suffix can match anywhere.
                    $q->where('mobile_no', 'like', $maskedPhonePattern)
                      ->orWhere('mobile_no2', 'like', $maskedPhonePattern)
                      ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile_no, '')) LIKE ?", [$maskedPhonePattern])
                      ->orWhereRaw("CONCAT(IFNULL(countrycode2, ''), IFNULL(mobile_no2, '')) LIKE ?", [$maskedPhonePattern]);

                    if ($withoutLeadingZeroPattern !== $maskedPhonePattern) {
                        $q->orWhere('mobile_no', 'like', $withoutLeadingZeroPattern)
                          ->orWhere('mobile_no2', 'like', $withoutLeadingZeroPattern);
                    }
                });
            }

            // Masked email (e.g. cr*****3@gmail.com)
            if (strpos($term, '@') !== false) {
                $cleanMaskedEmail = preg_replace('/\*+/', '%', $term);
                $query->orWhere('email', 'like', $cleanMaskedEmail);
            }
        }

        // 2. Name & Email substring
        $query->orWhere('name', 'like', '%' . $term . '%')
              ->orWhere('email', 'like', '%' . $term . '%');

        // 3. Clean digits (full phone number, last 10, with/without countrycode) - only when NOT masked
        if (!$hasAsterisk) {
            $cleanDigits = preg_replace('/\D+/', '', $term);
            if (strlen($cleanDigits) >= 4) {
                $last10 = strlen($cleanDigits) >= 10 ? substr($cleanDigits, -10) : $cleanDigits;
                $withoutZero = ltrim($cleanDigits, '0');

                $query->orWhere('mobile_no', 'like', '%' . $cleanDigits . '%')
                      ->orWhere('mobile_no2', 'like', '%' . $cleanDigits . '%')
                      ->orWhere('mobile_no', 'like', '%' . $last10 . '%')
                      ->orWhere('mobile_no2', 'like', '%' . $last10 . '%')
                      ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile_no, '')) LIKE ?", ['%' . $cleanDigits . '%'])
                      ->orWhereRaw("CONCAT(IFNULL(countrycode2, ''), IFNULL(mobile_no2, '')) LIKE ?", ['%' . $cleanDigits . '%']);

                if (!empty($withoutZero)) {
                    $query->orWhere('mobile_no', 'like', '%' . $withoutZero . '%')
                          ->orWhere('mobile_no2', 'like', '%' . $withoutZero . '%');
                }
            }
        }

        if (is_numeric($term)) {
            $query->orWhere('id', (int) $term);
        }

        $ids = $query->pluck('id')->toArray();
        if (is_numeric($term)) {
            $intId = (int) $term;
            if (!in_array($intId, $ids)) {
                $ids[] = $intId;
            }
        }

        return $ids;
    }
}

if (!function_exists('crm_email_account_id')) {
    /**
     * Resolve CRM mailbox IDs without allowing the default mailbox to override
     * the requested Client or Writer channel.
     */
    function crm_email_account_id(string $channel): int
    {
        $channel = strtolower(trim($channel));
        $isClient = $channel === 'client';
        $fallbackId = $isClient ? 2 : 1;
        $emailAddress = $isClient
            ? 'order@assignnmentinneed.com'
            : 'assignmentinneedhelp@gmail.com';
        $accountName = $isClient ? 'Client' : 'Writer';

        return (int) \Illuminate\Support\Facades\Cache::remember(
            'crm_email_account_id_' . $channel,
            3600,
            function () use ($emailAddress, $accountName, $fallbackId) {
                $account = \App\Models\EmailConfiguration::query()
                    ->where('is_active', true)
                    ->where(function ($query) use ($emailAddress, $accountName) {
                        $query->where('email_address', $emailAddress)
                            ->orWhere('name', $accountName);
                    })
                    ->orderByRaw('CASE WHEN email_address = ? THEN 0 ELSE 1 END', [$emailAddress])
                    ->first();

                return $account?->id ?? $fallbackId;
            }
        );
    }
}

if (!function_exists('logActivity')) {
    function logActivity($module, $action)
    {
        
        if (!Auth::check()) {
            return; 
        }
        try {
            UserLog::create([
                'user_id' => Auth::id(),
                'module' => $module,
                'action' => $action,
            ]);
            return ['status' => true, 'msg' => 'Log saved'];
        } catch (\Exception $e) {
            return ['status' => false, 'msg' => $e->getMessage()];
            // ignore error
        }
    }
}

if (!function_exists('get_order_duration_gap_badge')) {
    /**
     * Calculate gap (in days) between order_date and delivery_date (deadline)
     * and render styled label badge under order code.
     *
     * NOTE: Only shown for orders with status 'Initiated' or 'Other'.
     *
     * Ranges:
     * - <= 2 Days : < 2 Days (Red)
     * - 3-5 Days  : 3-5 Days (Warning/Yellow)
     * - 6-15 Days : 6-15 Days (Primary/Blue)
     * - > 15 Days : 15 Days and Above (Success/Green)
     */
    function get_order_duration_gap_badge($orderOrDate, $deliveryDate = null, $customStatus = null): string
    {
        try {
            $orderDate = null;
            $endDate = null;
            $status = $customStatus;

            if (is_object($orderOrDate)) {
                $status = $customStatus ?? ($orderOrDate->projectstatus ?? ($orderOrDate->status ?? ''));
                $orderDate = $orderOrDate->order_date ?? ($orderOrDate->created_at ?? null);
                $endDate = $deliveryDate ?? ($orderOrDate->delivery_date ?? ($orderOrDate->deadline ?? null));

                // Also check optional lead / frontendLead if attached
                if (empty($orderDate) && isset($orderOrDate->lead)) {
                    $orderDate = $orderOrDate->lead->created_at ?? ($orderOrDate->lead->create_at ?? null);
                }
                if (empty($endDate) && isset($orderOrDate->lead)) {
                    $endDate = $orderOrDate->lead->deadline ?? null;
                }
                if (empty($endDate) && isset($orderOrDate->frontendLead)) {
                    $endDate = $orderOrDate->frontendLead->deadline ?? null;
                }
            } elseif (is_array($orderOrDate)) {
                $status = $customStatus ?? ($orderOrDate['projectstatus'] ?? ($orderOrDate['status'] ?? ''));
                $orderDate = $orderOrDate['order_date'] ?? ($orderOrDate['created_at'] ?? null);
                $endDate = $deliveryDate ?? ($orderOrDate['delivery_date'] ?? ($orderOrDate['deadline'] ?? null));
            } else {
                $orderDate = $orderOrDate;
                $endDate = $deliveryDate;
            }

            // Only display for orders with status 'Initiated' or 'Other'
            $cleanStatus = strtolower(trim((string)$status));
            if (!in_array($cleanStatus, ['initiated', 'other'])) {
                return '';
            }

            if (empty($orderDate) || empty($endDate)) {
                return '';
            }

            $start = \Carbon\Carbon::parse($orderDate)->startOfDay();
            $end = \Carbon\Carbon::parse($endDate)->startOfDay();
            $days = (int) $start->diffInDays($end, false);

            if ($days <= 2) {
                $text = '&lt; 2 Days';
                $badgeClass = 'badge-light-danger text-danger';
                $border = 'border: 1px solid rgba(241, 65, 108, 0.3);';
            } elseif ($days >= 3 && $days <= 5) {
                $text = '3-5 Days';
                $badgeClass = 'badge-light-warning text-warning';
                $border = 'border: 1px solid rgba(255, 199, 0, 0.3);';
            } elseif ($days >= 6 && $days <= 15) {
                $text = '6-15 Days';
                $badgeClass = 'badge-light-primary text-primary';
                $border = 'border: 1px solid rgba(0, 158, 247, 0.3);';
            } else {
                $text = '15 Days and Above';
                $badgeClass = 'badge-light-success text-success';
                $border = 'border: 1px solid rgba(80, 205, 137, 0.3);';
            }

            return '<div class="mt-1"><span class="badge ' . $badgeClass . ' fs-8 fw-bold" style="' . $border . ' border-radius: 5px; padding: 3px 8px;" title="Duration: ' . $days . ' Days (' . $start->format('d M Y') . ' to ' . $end->format('d M Y') . ')">' . $text . '</span></div>';
        } catch (\Throwable $e) {
            return '';
        }
    }
}

if (!function_exists('get_blog_cta_definitions')) {
    /**
     * Master definitions for the 6 Blog CTAs.
     */
    function get_blog_cta_definitions(): array
    {
        return [
            1 => [
                'id' => 1,
                'key' => 'CTA_ASSIGNMENT_HELP',
                'title' => 'Expert Assignment Help Today',
                'slug' => 'assignment-help-today',
                'url' => 'https://www.assignmentinneed.co.uk/order',
                'icon' => '🏴',
                'fa_icon' => 'fa-flag',
                'files' => ['cta-1.webp', 'cta-1.png', 'cta-1.jpg', 'cta-1-best-country-finder.png', 'cta-1-best-country-finder.webp', 'cta-1.svg'],
            ],
            2 => [
                'id' => 2,
                'key' => 'CTA_MEET_YOUR_DEADLINE',
                'title' => 'Meet Your Deadline',
                'slug' => 'meet-your-deadline',
                'url' => 'https://www.assignmentinneed.co.uk/order',
                'icon' => '🧮',
                'fa_icon' => 'fa-calculator',
                'files' => ['cta-2.webp', 'cta-2.png', 'cta-2.jpg', 'cta-2-calculate-loan-emi.png', 'cta-2-calculate-loan-emi.webp', 'cta-2.svg'],
            ],
            3 => [
                'id' => 3,
                'key' => 'CTA_BETTER_GRADE_LESS_STRESS',
                'title' => 'Better Grade Less Stress',
                'slug' => 'better-grade-less-stress',
                'url' => 'https://www.assignmentinneed.co.uk/order',
                'icon' => '🏛️',
                'fa_icon' => 'fa-university',
                'files' => ['cta-3.webp', 'cta-3.png', 'cta-3.jpg', 'cta-3-university-shortlist.png', 'cta-3-university-shortlist.webp', 'cta-3.svg'],
            ],
            4 => [
                'id' => 4,
                'key' => 'CTA_TRUSTED_BY_THOUSANDS',
                'title' => 'Trusted By Thousands',
                'slug' => 'trustd-by-thousands',
                'url' => 'https://www.assignmentinneed.co.uk/order',
                'icon' => '🎓',
                'fa_icon' => 'fa-graduation-cap',
                'files' => ['cta-4.webp', 'cta-4.png', 'cta-4.jpg', 'cta-4-scholarships.png', 'cta-4-scholarships.webp', 'cta-4.svg'],
            ],
            5 => [
                'id' => 5,
                'key' => 'CTA_YOU_LOVE_WE_SUPPORT',
                'title' => 'You Love We Support',
                'slug' => 'you-love-we-support',
                'url' => 'https://www.assignmentinneed.co.uk/order',
                'icon' => '💰',
                'fa_icon' => 'fa-money-bill-wave',
                'files' => ['cta-5.webp', 'cta-5.png', 'cta-5.jpg', 'cta-5-calculate-study-cost.png', 'cta-5-calculate-study-cost.webp', 'cta-5.svg'],
            ],
            6 => [
                'id' => 6,
                'key' => 'CTA_SUCCESS_IS_PRIORITY',
                'title' => 'Your Success is our Priority',
                'slug' => 'your-success-is-our-priority',
                'url' => 'https://www.assignmentinneed.co.uk/order',
                'icon' => '⚡',
                'fa_icon' => 'fa-bolt',
                'files' => ['cta-6.webp', 'cta-6.png', 'cta-6.jpg', 'cta-6-profile-evaluation.png', 'cta-6-profile-evaluation.webp', 'cta-6.svg'],
            ],
        ];
    }
}

if (!function_exists('get_blog_cta_relative_path')) {
    /**
     * Resolve relative image path (e.g. /assets/media/blog-cta/1.png) for a given CTA ID.
     */
    function get_blog_cta_relative_path(int $ctaId): string
    {
        $defs = get_blog_cta_definitions();
        if (!isset($defs[$ctaId])) {
            return '';
        }

        $dir = public_path('assets/media/blog-cta');
        $extensions = ['webp', 'png', 'jpg', 'jpeg', 'svg'];
        $slug = $defs[$ctaId]['slug'] ?? ('cta-' . $ctaId);

        // Check direct numeric {id} files (1.png, 2.png), custom named files, or cta-{id} files
        foreach ([(string) $ctaId, 'cta-' . $ctaId, $slug] as $base) {
            foreach ($extensions as $ext) {
                if (file_exists($dir . DIRECTORY_SEPARATOR . $base . '.' . $ext)) {
                    return '/assets/media/blog-cta/' . $base . '.' . $ext;
                }
            }
        }

        foreach ($defs[$ctaId]['files'] as $f) {
            if (file_exists($dir . DIRECTORY_SEPARATOR . $f)) {
                return '/assets/media/blog-cta/' . $f;
            }
        }

        return '/assets/media/blog-cta/' . $defs[$ctaId]['files'][0];
    }
}

if (!function_exists('get_blog_cta_image_url')) {
    /**
     * Resolve image URL for a given CTA ID. Checks uploaded PNG/WebP/JPG first, then fallback SVG.
     * Supports relative paths, dynamic host detection, and APP_URL.
     */
    function get_blog_cta_image_url(int $ctaId, ?bool $relative = null): string
    {
        $relPath = get_blog_cta_relative_path($ctaId);
        if (empty($relPath)) {
            return '';
        }

        // 1. Explicit relative requested, or via query/header parameter (?relative_urls=1 or X-Relative-Urls: true)
        if ($relative === true || (function_exists('request') && (request()->query('relative_urls') == '1' || request()->header('X-Relative-Urls') == 'true'))) {
            return $relPath;
        }

        // 2. If APP_URL in .env/config is a production or UAT domain
        $configAppUrl = rtrim((string) config('app.url', ''), '/');
        if (!empty($configAppUrl) && !str_contains($configAppUrl, '127.0.0.1') && !str_contains($configAppUrl, 'localhost')) {
            return $configAppUrl . $relPath;
        }

        // 3. Fallback to asset() which automatically binds to the active domain/host
        return asset(ltrim($relPath, '/'));
    }
}

if (!function_exists('render_blog_ctas')) {
    /**
     * Transforms blog content containing CTA tokens or placeholder blocks into responsive images for frontend display.
     */
    function render_blog_ctas(?string $content, ?bool $relativeUrls = null): string
    {
        if (empty($content)) {
            return '';
        }

        $defs = get_blog_cta_definitions();

        foreach ($defs as $ctaId => $cta) {
            $key = $cta['key'];
            $relPath = get_blog_cta_relative_path($ctaId);
            $imgUrl = get_blog_cta_image_url($ctaId, $relativeUrls);
            $title = htmlspecialchars($cta['title'], ENT_QUOTES, 'UTF-8');
            $targetUrl = $cta['url'] ?? 'https://www.assignmentinneed.co.uk/order';

            $bannerHtml = '<div class="blog-cta-wrapper blog-cta-' . $ctaId . '" data-cta-id="' . $ctaId . '" data-cta-key="' . $key . '" style="display: block; width: 100%; max-width: 100%; margin: 28px auto; text-align: center; clear: both;">'
                . '<a href="' . $targetUrl . '" target="_blank" rel="noopener noreferrer" class="blog-cta-link" style="display: block; width: 100%; max-width: 100%; text-decoration: none; margin: 0 auto;">'
                . '<img src="' . $imgUrl . '" data-relative-src="' . $relPath . '" alt="' . $title . '" class="img-fluid blog-cta-img" data-cta-id="' . $ctaId . '" data-cta-key="' . $key . '" style="display: block; width: 100%; max-width: 100%; height: auto; margin: 0 auto; cursor: pointer;" loading="lazy" />'
                . '</a>'
                . '</div>';

            // 1. Replace outer placeholder div/p if present
            $divPattern = '/<(?:div|p)[^>]*data-cta-key=["\'][^"\']*' . preg_quote($key, '/') . '[^"\']*["\'][^>]*>.*?<\/(?:div|p)>/is';
            $content = preg_replace($divPattern, $bannerHtml, $content);

            // 2. Replace standalone [CTA_KEY] token
            $tokenPattern = '/\[' . preg_quote($key, '/') . '\]/is';
            $content = preg_replace($tokenPattern, $bannerHtml, $content);
        }

        return $content;
    }
}
