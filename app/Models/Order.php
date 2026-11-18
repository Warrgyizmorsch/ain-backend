<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'uid',
        'wid',
        'swid',
        'order_id',
        'status',
        'title',
        'description',
        'amount',
        'received_amount',
        'currency',
        'created_at',
        'updated_at',
        'lead_id',
        'writer_id',
        'team_id',
        'team_assigned_at',
        'marks',
        'offer',
        'referal',
        'client_will_refer',
        'failed_at',
        'projectstatus',
        'module_code',
        'pages',
        'delivery_date',
        'delivery_time',
        'services',
        'typeofpaper',
        'message',
        'tech',
        'resit',
        'coupon_code',
        'coupon_discount_type',
        'coupon_discount_value',
        'coupon_discount_amount',
        'coupon_original_amount',
        'referred_by_user_id',
        'referral_bonus_awarded_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'referral_bonus_awarded_at' => 'datetime',
    ];

    public function getReceivedAmountAttribute($value)
    {
        if ($this->relationLoaded('payment') && $this->payment && $this->payment->count() > 0) {
            return (float) $this->payment->filter(function ($p) {
                return empty($p->is_revoked) || $p->is_revoked == 0;
            })->sum('paid_amount');
        }

        return is_numeric($value) ? (float) $value : 0.0;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    public function referredByUser()
    {
        return $this->belongsTo(User::class, 'referred_by_user_id');
    }

    protected static function booted()
    {
        static::saved(function ($order) {
            app(\App\Services\ReferralCodeService::class)->handleEligibleOrder(
                $order,
                null,
                \Illuminate\Support\Facades\Auth::user()
            );
        });
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'order_id', 'id');
    }

    public function ordercall()
    {
        return $this->hasMany(Ordercall::class, 'order_id', 'id')->with('user');
    }

    public function writer()
    {
        return $this->belongsTo(User::class, 'wid');
    }

    public function subwriter()
    {
        return $this->belongsTo(User::class, 'swid');
    }

    public function mulsubwriter()
    {
        return $this->hasMany(multipleswiter::class, 'order_id', 'id')->with('user');
    }

    public function order()
    {
        return $this->belongsTo(multipleswiter::class, 'order_id');
    }

    public function followUpComments()
    {
        return $this->hasMany(FollowUpComment::class, 'order_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function lead()
    {
        return $this->belongsTo(Leads::class, 'lead_id');
    }

    public function frontendLead()
    {
        return $this->hasOne(Leads::class, 'order_id', 'order_id');
    }

    public function additionals()
    {
        return $this->hasMany(\App\Models\Additional::class, 'order_id', 'order_id');
    }

    public function isInitiatedStatus(): bool
    {
        return strtolower(trim((string) $this->projectstatus)) === 'initiated';
    }

    /**
     * Scope to genuine, placed, converted, non-cancelled customer orders.
     * Excludes unconverted lead placeholders (leads.is_converted = 0 matched on integer FK lead_id),
     * cancelled/trashed orders, and incomplete un-dated Pending/empty checkout drafts.
     */
    public function scopeGenuinePlaced($query, string $tableAlias = 'orders')
    {
        return $query
            ->whereNotNull("{$tableAlias}.uid")
            ->where("{$tableAlias}.uid", '>', 0)
            ->whereRaw("LOWER(TRIM(COALESCE({$tableAlias}.projectstatus, ''))) NOT IN ('cancelled', 'trash')")
            ->where(function ($q) use ($tableAlias) {
                $q->whereNotNull("{$tableAlias}.order_date")
                  ->orWhereRaw("LOWER(TRIM(COALESCE({$tableAlias}.projectstatus, ''))) NOT IN ('', 'pending')");
            })
            ->whereNotExists(function ($sub) use ($tableAlias) {
                $sub->select(DB::raw(1))
                    ->from('leads as gl')
                    ->whereColumn('gl.id', "{$tableAlias}.lead_id")
                    ->where('gl.is_converted', 0);
            });
    }

    /**
     * Parse client order category / minimum count filter into [min, max] bounds.
     *
     * @return array{min: int, max: ?int}|null
     */
    public static function resolveClientOrderBounds(?string $category, $minOrders = null): ?array
    {
        $cat = strtolower(trim((string) $category));
        $bounds = null;

        if ($cat !== '') {
            $bounds = match ($cat) {
                'multiple', 'multi', '2+' => ['min' => 2, 'max' => null],
                '2_to_3', 'repeat_2_3', '2-3' => ['min' => 2, 'max' => 3],
                'retainer', 'repeated', '4+' => ['min' => 4, 'max' => null],
                'loyal', '10+', '>10' => ['min' => 11, 'max' => null],
                'single', '1', 'beginner_1' => ['min' => 1, 'max' => 1],
                'beginner', '1_to_3', '1-3' => ['min' => 1, 'max' => 3],
                default => null,
            };
        }

        if ($minOrders !== null && $minOrders !== '' && is_numeric($minOrders)) {
            $minInt = (int) $minOrders;
            if ($minInt >= 1) {
                if ($bounds === null) {
                    $bounds = ['min' => $minInt, 'max' => null];
                } else {
                    $bounds['min'] = max($bounds['min'], $minInt);
                    if ($bounds['max'] !== null && $bounds['min'] > $bounds['max']) {
                        $bounds['max'] = null;
                    }
                }
            }
        }

        return $bounds;
    }

    /**
     * Resolve optional [from, to] order_date range from an order_period preset and/or explicit dates.
     *
     * @return array{0: ?string, 1: ?string}
     */
    public static function resolveOrderPeriodDateRange(?string $orderPeriod, ?string $fromDate = null, ?string $toDate = null): array
    {
        $period = strtolower(trim((string) $orderPeriod));
        if ($fromDate || $toDate) {
            return [$fromDate ?: null, $toDate ?: null];
        }

        return match ($period) {
            '18_months' => [Carbon::now()->subMonths(18)->toDateString(), null],
            'this_year' => [Carbon::now()->startOfYear()->toDateString(), Carbon::now()->endOfYear()->toDateString()],
            'lifetime', 'all', '' => [null, null],
            default => [null, null],
        };
    }

    /**
     * Build derived aggregate subquery of genuine active customers (role_id = 2, flag = 0)
     * with counts strictly over genuine, placed, converted, non-cancelled orders.
     */
    public static function genuineClientAggregateSubquery(?array $bounds = null, ?string $orderFrom = null, ?string $orderTo = null)
    {
        $sub = DB::table('orders as gc_o')
            ->join('users as gc_u', 'gc_u.id', '=', 'gc_o.uid')
            ->leftJoin('leads as gc_l', function ($join) {
                $join->on('gc_l.id', '=', 'gc_o.lead_id')
                     ->where('gc_l.is_converted', '=', 0);
            })
            ->where('gc_u.role_id', 2)
            ->where('gc_u.flag', 0)
            ->whereNotNull('gc_o.uid')
            ->where('gc_o.uid', '>', 0)
            ->whereNull('gc_l.id')
            ->whereRaw("LOWER(TRIM(COALESCE(gc_o.projectstatus, ''))) NOT IN ('cancelled', 'trash')")
            ->where(function ($q) {
                $q->whereNotNull('gc_o.order_date')
                  ->orWhereRaw("LOWER(TRIM(COALESCE(gc_o.projectstatus, ''))) NOT IN ('', 'pending')");
            });

        if ($orderFrom && $orderTo) {
            $sub->whereBetween('gc_o.order_date', [$orderFrom, $orderTo]);
        } elseif ($orderFrom) {
            $sub->where('gc_o.order_date', '>=', $orderFrom);
        } elseif ($orderTo) {
            $sub->where('gc_o.order_date', '<=', $orderTo);
        }

        $sub->selectRaw('gc_o.uid as client_uid, COUNT(*) as genuine_orders_count')
            ->groupBy('gc_o.uid');

        if ($bounds !== null) {
            $min = (int) ($bounds['min'] ?? 1);
            $max = isset($bounds['max']) ? (int) $bounds['max'] : null;

            if ($max !== null && $min === $max) {
                $sub->havingRaw('COUNT(*) = ?', [$min]);
            } elseif ($max !== null) {
                $sub->havingRaw('COUNT(*) BETWEEN ? AND ?', [$min, $max]);
            } else {
                $sub->havingRaw('COUNT(*) >= ?', [$min]);
            }
        }

        return $sub;
    }

    public function assignTeamForInitiatedStatus(bool $allowNonInitiated = false): void
    {
        if ((!$allowNonInitiated && !$this->isInitiatedStatus()) || !empty($this->team_id)) {
            return;
        }

        DB::transaction(function () use ($allowNonInitiated) {
            $order = self::whereKey($this->getKey())->lockForUpdate()->first();

            if (!$order || (!$allowNonInitiated && !$order->isInitiatedStatus()) || !empty($order->team_id)) {
                return;
            }

            $targetTeamId = null;

            // 1. Check Sticky Assignment (Lifetime): If customer already has ANY order assigned to a team
            if (!empty($order->uid)) {
                $existingTeamOrder = self::where('uid', $order->uid)
                    ->whereNotNull('team_id')
                    ->where('id', '!=', $order->id)
                    ->orderByDesc('id')
                    ->first();

                if ($existingTeamOrder) {
                    $targetTeamId = $existingTeamOrder->team_id;
                }
            }

            // 2. Check Referral Inheritance: If user was referred by someone (refer_id) who has a team assigned
            if (!$targetTeamId && !empty($order->uid)) {
                $customer = User::find($order->uid);
                if ($customer && !empty($customer->refer_id)) {
                    $referrerTeamOrder = self::where('uid', $customer->refer_id)
                        ->whereNotNull('team_id')
                        ->orderByDesc('id')
                        ->first();

                    if ($referrerTeamOrder) {
                        $targetTeamId = $referrerTeamOrder->team_id;
                    }
                }
            }

            // If Sticky or Referral team found, assign immediately and sync all unassigned orders of this customer
            if ($targetTeamId) {
                $order->team_id = $targetTeamId;
                $order->team_assigned_at = now();
                $order->save();

                if (!empty($order->uid)) {
                    self::where('uid', $order->uid)
                        ->whereNull('team_id')
                        ->where('id', '!=', $order->id)
                        ->update([
                            'team_id' => $targetTeamId,
                            'team_assigned_at' => now(),
                        ]);
                }

                $this->forceFill([
                    'team_id' => $order->team_id,
                    'team_assigned_at' => $order->team_assigned_at,
                ]);
                return;
            }

            // 3. Dynamic Multi-Team Balancing (for any number of teams: 2, 3, 5, etc.)
            $teams = Team::where('is_delete', 0)
                ->orderBy('priority', 'asc')
                ->get();

            if ($teams->isEmpty()) {
                return;
            }

            $totalAssignedTodayQuery = self::whereDate('updated_at', Carbon::today())
                ->whereNotNull('team_id');

            if (!$allowNonInitiated) {
                $totalAssignedTodayQuery->whereRaw('LOWER(projectstatus) = ?', ['initiated']);
            }

            $totalAssignedToday = $totalAssignedTodayQuery->count();

            $allocations = [];
            $assignedCount = [];

            foreach ($teams as $team) {
                $allocations[$team->id] = floor(((float) $team->percentage / 100) * ($totalAssignedToday + 1));
                $assignedCountQuery = self::whereDate('updated_at', Carbon::today())
                    ->where('team_id', $team->id);

                if (!$allowNonInitiated) {
                    $assignedCountQuery->whereRaw('LOWER(projectstatus) = ?', ['initiated']);
                }

                $assignedCount[$team->id] = $assignedCountQuery->count();
            }

            $chosenTeamId = null;
            foreach ($teams as $team) {
                if ($assignedCount[$team->id] < $allocations[$team->id]) {
                    $chosenTeamId = $team->id;
                    break;
                }
            }

            if (!$chosenTeamId) {
                $chosenTeamId = collect($assignedCount)->sort()->keys()->first();
            }

            if ($chosenTeamId) {
                $order->team_id = $chosenTeamId;
                $order->team_assigned_at = now();
                $order->save();

                // Sync all unassigned orders of this customer to this team
                if (!empty($order->uid)) {
                    self::where('uid', $order->uid)
                        ->whereNull('team_id')
                        ->where('id', '!=', $order->id)
                        ->update([
                            'team_id' => $chosenTeamId,
                            'team_assigned_at' => now(),
                        ]);
                }

                $this->forceFill([
                    'team_id' => $order->team_id,
                    'team_assigned_at' => $order->team_assigned_at,
                ]);
            }
        });
    }
}
