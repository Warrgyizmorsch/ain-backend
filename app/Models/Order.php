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
        'coupon_original_amount'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
