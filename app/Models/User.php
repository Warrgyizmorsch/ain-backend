<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $appends = [
        'customer_type'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'mobile_no',
        'countrycode',
        'team_id',
        'refer_id',
        'referral_code',
        'total_referral_earnings',
        'Wallet',
        'verifyed',
        'otp',
        'photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected $attributes = [
        'role_id' => 2,
    ];

    protected static function booted()
    {
        static::saved(function ($user) {
            if (!empty($user->email) || !empty($user->mobile_no)) {
                try {
                    app(\App\Services\LabelSyncService::class)->syncOnContactCreatedOrUpdated(
                        $user->email,
                        $user->countrycode,
                        $user->mobile_no,
                        auth()->id()
                    );
                } catch (\Throwable $e) {
                    \Log::warning('Label auto-sync error on User save: ' . $e->getMessage());
                }
            }
        });
    }
    
     public function writerWork()
    {
        return $this->hasMany(multipleswiter::class, 'user_id', 'id')->with('order');
    }
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function orders() {
        return $this->hasMany(Order::class, 'uid', 'id');
    }

    public function leads()
    {
        return $this->hasMany(Leads::class, 'emp_id', 'id');
    }
    public function groups() { return $this->belongsToMany(GroupMaster::class)->withTimestamps(); }

    /**
     * Get assigned CRM labels for the user (applies to all user orders and profile)
     * Strictly restricted to labels where is_crm == true.
     */
    public function getLabelsAttribute()
    {
        if ($this->relationLoaded('labels')) {
            return $this->getRelation('labels');
        }

        $labelIds = collect();

        // 1. Direct CRM user labels by user_id
        $crmIds = \App\Models\CrmUserLabel::where('user_id', $this->id)->pluck('label_id');
        $labelIds = $labelIds->concat($crmIds);

        $phones = [];
        if (!empty($this->mobile_no)) {
            $raw = trim($this->mobile_no);
            $clean = preg_replace('/\D+/', '', $raw);
            $cc = preg_replace('/\D+/', '', (string)($this->countrycode ?? ''));
            $full = $cc . $clean;
            $last10 = strlen($clean) >= 10 ? substr($clean, -10) : $clean;
            $phones = array_values(array_unique(array_filter([
                $raw, $clean, $full, '+' . $full, $last10, '+91' . $last10, '91' . $last10, '+44' . $last10, '44' . $last10,
            ])));
        }

        // 2. Fallback / supplementary matching by phone & email in crm_user_labels
        if (!empty($phones)) {
            $crmPhoneIds = \App\Models\CrmUserLabel::whereIn('phone', $phones)->pluck('label_id');
            $labelIds = $labelIds->concat($crmPhoneIds);
        }

        if (!empty($this->email)) {
            $crmEmailIds = \App\Models\CrmUserLabel::where('email', $this->email)->pluck('label_id');
            $labelIds = $labelIds->concat($crmEmailIds);
        }

        // 3. Fallback to contact/thread labels (strictly for backward-compat if crm table has not yet synced)
        if ($labelIds->isEmpty()) {
            if (!empty($phones)) {
                $waIds = WhatsappChatContactLabel::whereIn('phone', $phones)->pluck('label_id');
                $labelIds = $labelIds->concat($waIds);
            }
            if (!empty($this->email)) {
                $emailIds = \App\Models\EmailThreadLabel::where('email', $this->email)->pluck('label_id');
                $labelIds = $labelIds->concat($emailIds);
            }
        }

        $uniqueIds = $labelIds->unique()->filter()->all();
        if (empty($uniqueIds)) {
            $emptyCollection = collect();
            $this->setRelation('labels', $emptyCollection);
            return $emptyCollection;
        }

        // Strictly enforce forCrm() so non-CRM labels never show in CRM / Orders
        $labels = WhatsappChatLabel::forCrm()->whereIn('id', $uniqueIds)->ordered()->get();
        $this->setRelation('labels', $labels);
        return $labels;
    }

    /**
     * Batch attach CRM labels to a collection of users to prevent N+1 queries.
     * Strictly restricted to labels where is_crm == true.
     */
    public static function attachLabelsToUsers($users)
    {
        if (empty($users)) return;

        $userList = collect($users)->filter()->unique('id');
        if ($userList->isEmpty()) return;

        $allPhones = [];
        $userPhoneMap = [];
        $userEmailMap = [];
        $allEmails = [];
        $userIds = $userList->pluck('id')->all();
        $userLabelIds = [];

        foreach ($userList as $u) {
            $uId = $u->id;
            if (!empty($u->mobile_no)) {
                $raw = trim($u->mobile_no);
                $clean = preg_replace('/\D+/', '', $raw);
                $cc = preg_replace('/\D+/', '', (string)($u->countrycode ?? ''));
                $full = $cc . $clean;
                $last10 = strlen($clean) >= 10 ? substr($clean, -10) : $clean;
                $variants = array_values(array_unique(array_filter([
                    $raw, $clean, $full, '+' . $full, $last10, '+91' . $last10, '91' . $last10, '+44' . $last10, '44' . $last10
                ])));
                foreach ($variants as $p) {
                    $allPhones[] = $p;
                    $userPhoneMap[$p][] = $uId;
                }
            }
            if (!empty($u->email)) {
                $cleanEmail = strtolower(trim($u->email));
                $allEmails[] = $cleanEmail;
                $userEmailMap[$cleanEmail][] = $uId;
            }
        }

        // 1. Direct fetch from crm_user_labels by user_id
        $crmUserLabels = \App\Models\CrmUserLabel::whereIn('user_id', $userIds)->get(['user_id', 'label_id']);
        foreach ($crmUserLabels as $cul) {
            $userLabelIds[$cul->user_id][] = (int) $cul->label_id;
        }

        // 2. Fetch by phone / email in crm_user_labels
        if (!empty($allPhones)) {
            $crmPhoneLabels = \App\Models\CrmUserLabel::whereIn('phone', array_unique($allPhones))->whereNull('user_id')->get(['phone', 'label_id']);
            foreach ($crmPhoneLabels as $cl) {
                if (isset($userPhoneMap[$cl->phone])) {
                    foreach ($userPhoneMap[$cl->phone] as $uId) {
                        $userLabelIds[$uId][] = (int) $cl->label_id;
                    }
                }
            }
        }

        if (!empty($allEmails)) {
            $crmEmailLabels = \App\Models\CrmUserLabel::whereIn('email', array_unique($allEmails))->whereNull('user_id')->get(['email', 'label_id']);
            foreach ($crmEmailLabels as $el) {
                $elEmail = strtolower(trim($el->email));
                if (isset($userEmailMap[$elEmail])) {
                    foreach ($userEmailMap[$elEmail] as $uId) {
                        $userLabelIds[$uId][] = (int) $el->label_id;
                    }
                }
            }
        }

        // 3. Backward-compat fallback if user has no crm_user_label records yet
        $usersWithoutCrmLabels = $userList->filter(fn($u) => empty($userLabelIds[$u->id]));
        if ($usersWithoutCrmLabels->isNotEmpty()) {
            $missingPhones = [];
            $missingEmails = [];
            foreach ($usersWithoutCrmLabels as $u) {
                if (!empty($u->mobile_no)) {
                    $missingPhones[] = preg_replace('/\D+/', '', $u->mobile_no);
                }
                if (!empty($u->email)) {
                    $missingEmails[] = strtolower(trim($u->email));
                }
            }
            if (!empty($missingPhones)) {
                $contactLabels = WhatsappChatContactLabel::whereIn('phone', array_unique($missingPhones))->get(['phone', 'label_id']);
                foreach ($contactLabels as $cl) {
                    if (isset($userPhoneMap[$cl->phone])) {
                        foreach ($userPhoneMap[$cl->phone] as $uId) {
                            $userLabelIds[$uId][] = (int)$cl->label_id;
                        }
                    }
                }
            }
            if (!empty($missingEmails)) {
                $emailLabels = \App\Models\EmailThreadLabel::whereIn('email', array_unique($missingEmails))->get(['email', 'label_id']);
                foreach ($emailLabels as $el) {
                    $elEmail = strtolower(trim($el->email));
                    if (isset($userEmailMap[$elEmail])) {
                        foreach ($userEmailMap[$elEmail] as $uId) {
                            $userLabelIds[$uId][] = (int)$el->label_id;
                        }
                    }
                }
            }
        }

        $allLabelIds = collect($userLabelIds)->flatten()->unique()->filter()->all();
        // Strictly filter by forCrm()
        $labelsById = !empty($allLabelIds) ? WhatsappChatLabel::forCrm()->whereIn('id', $allLabelIds)->ordered()->get()->keyBy('id') : collect();

        foreach ($userList as $u) {
            $uId = $u->id;
            $ids = array_unique($userLabelIds[$uId] ?? []);
            $labels = collect($ids)->map(fn($id) => $labelsById->get($id))->filter()->values();
            $u->setRelation('labels', $labels);
        }
    }

    public function followups()
    {
        // Yahan 'Followup::class' ko apne actual follow-up model se replace karein
        return $this->hasMany(FollowUpComment::class, 'uid'); 
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'refer_id', 'id');
    }

    public function referredUsers()
    {
        return $this->hasMany(User::class, 'refer_id', 'id');
    }

    public static function generateUniqueReferralCode(): string
    {
        do {
            $code = 'REF' . strtoupper(\Illuminate\Support\Str::random(6));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Dynamic Customer Categorization:
     * - Loyal Customer: Total orders > 10
     * - Retainer Customer: First order was 9+ months ago (and > 1 orders)
     * - Repeated Customer: > 1 orders within 3 months of first order
     * - Beginner Customer: 1 order
     * - New Customer: 0 orders
     */
    public function getCustomerTypeAttribute()
    {
        $ordersCount = $this->orders()->count();
        if ($ordersCount === 0) {
            return 'New Customer';
        }

        // 1. Loyal Customer (> 10 Orders)
        if ($ordersCount > 10) {
            return 'Loyal Customer';
        }

        $firstOrder = $this->orders()->oldest('created_at')->first();
        if (!$firstOrder) {
            return 'New Customer';
        }

        $firstOrderDate = Carbon::parse($firstOrder->created_at);
        $monthsSinceFirstOrder = $firstOrderDate->diffInMonths(now());

        // 2. Retainer Customer (First order placed 9+ months ago AND has repeated purchases)
        if ($monthsSinceFirstOrder >= 9 && $ordersCount > 1) {
            return 'Retainer Customer';
        }

        // 3. Repeated Customer (> 1 orders & placed orders within 3 months)
        if ($ordersCount > 1 && $monthsSinceFirstOrder <= 3) {
            return 'Repeated Customer';
        }

        // 4. Beginner Customer (1 order or default)
        return 'Beginner Customer';
    }
}
