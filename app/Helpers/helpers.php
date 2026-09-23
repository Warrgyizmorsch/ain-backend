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
     * the mobile number in masked format (only last 4 digits visible).
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
        if (empty($cleanCC)) {
            return mask_raw_phone($mobile);
        }
        return mask_mobile_only($countryCode, $mobile);
    }
}

if (!function_exists('mask_mobile_only')) {
    /**
     * Masks only the mobile number part (without country code) for form fields & display.
     * Keeps country code separate.
     * Shows only asterisks + last 4 digits (e.g. ******9811).
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

        // If 11 digits starting with 0 (e.g. 07700... or 09610...), strip leading 0 for standard 10-digit mask
        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        $len = strlen($digits);
        if ($len <= 4) {
            return str_repeat('*', max(4, $len));
        }

        // Only asterisks + last 4 digits (e.g. ******9811), no leading digits
        return str_repeat('*', max(4, $len - 4)) . substr($digits, -4);
    }
}

if (!function_exists('mask_raw_phone')) {
    /**
     * Masks complete phone numbers with country code:
     * e.g. +44 ******3818 or ******9811
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
        if (str_starts_with($phoneStr, '+')) {
            if (preg_match('/^(\+(?:1|44|91|61|971|86|33|49|81|65|60|64|\d{1,2}))/', $phoneStr, $m)) {
                $prefix = $m[1] . ' ';
                $cleanCC = preg_replace('/\D+/', '', $m[1]);
                if (str_starts_with($digits, $cleanCC)) {
                    $digits = substr($digits, strlen($cleanCC));
                }
            }
        } elseif ((str_starts_with($digits, '91') || str_starts_with($digits, '44')) && strlen($digits) > 10) {
            $prefix = '+' . substr($digits, 0, 2) . ' ';
            $digits = substr($digits, 2);
        } elseif (strlen($digits) > 10) {
            $prefix = '+' . substr($digits, 0, strlen($digits) - 10) . ' ';
            $digits = substr($digits, -10);
        }

        $len = strlen($digits);
        if ($len <= 4) {
            return $prefix . str_repeat('*', max(4, $len));
        }

        // Country code + asterisks + last 4 digits (no leading digits)
        return $prefix . str_repeat('*', max(4, $len - 4)) . substr($digits, -4);
    }
}

if (!function_exists('mask_email_for_display')) {
    /**
     * Show full email only to Super Admins (role_id 1). Everyone else sees
     * masked email e.g. cr*****3@gmail.com
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
            return substr($email, 0, 2) . '****' . substr($email, -2);
        }

        $name = $parts[0];
        $domain = $parts[1];

        $len = strlen($name);
        if ($len <= 2) {
            $maskedName = substr($name, 0, 1) . '***';
        } elseif ($len <= 4) {
            $maskedName = substr($name, 0, 1) . '***' . substr($name, -1);
        } else {
            $maskedName = substr($name, 0, 2) . '*****' . substr($name, -1);
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
            $rawPattern = preg_replace('/\*+/', '%', preg_replace('/[^0-9*]/', '', $term));
            $cleanMaskedPhone = ltrim($rawPattern, '0');
            if (!empty($cleanMaskedPhone) && strpos($cleanMaskedPhone, '%') !== false && preg_match('/\d/', $cleanMaskedPhone)) {
                $query->where(function ($q) use ($cleanMaskedPhone, $rawPattern) {
                    $q->where('mobile_no', 'like', '%' . $cleanMaskedPhone . '%')
                      ->orWhere('mobile_no2', 'like', '%' . $cleanMaskedPhone . '%')
                      ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile_no, '')) LIKE ?", ['%' . $cleanMaskedPhone . '%'])
                      ->orWhereRaw("CONCAT(IFNULL(countrycode2, ''), IFNULL(mobile_no2, '')) LIKE ?", ['%' . $cleanMaskedPhone . '%']);
                    if ($rawPattern !== $cleanMaskedPhone) {
                        $q->orWhere('mobile_no', 'like', '%' . $rawPattern . '%')
                          ->orWhere('mobile_no2', 'like', '%' . $rawPattern . '%');
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
