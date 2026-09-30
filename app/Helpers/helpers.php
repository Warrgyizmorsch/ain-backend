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
            return e($text);
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

        return $escaped;
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
