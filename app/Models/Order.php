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

            $existingTeamOrder = self::where('uid', $order->uid)
                ->whereDate('updated_at', Carbon::today())
                ->whereNotNull('team_id')
                ->first();

            if ($existingTeamOrder) {
                $order->team_id = $existingTeamOrder->team_id;
                $order->team_assigned_at = now();
                $order->save();

                $this->forceFill([
                    'team_id' => $order->team_id,
                    'team_assigned_at' => $order->team_assigned_at,
                ]);
                return;
            }

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

            foreach ($teams as $team) {
                if ($assignedCount[$team->id] < $allocations[$team->id]) {
                    $order->team_id = $team->id;
                    $order->team_assigned_at = now();
                    $order->save();

                    $this->forceFill([
                        'team_id' => $order->team_id,
                        'team_assigned_at' => $order->team_assigned_at,
                    ]);
                    return;
                }
            }

            $fallbackTeamId = collect($assignedCount)->sort()->keys()->first();

            if ($fallbackTeamId) {
                $order->team_id = $fallbackTeamId;
                $order->team_assigned_at = now();
                $order->save();

                $this->forceFill([
                    'team_id' => $order->team_id,
                    'team_assigned_at' => $order->team_assigned_at,
                ]);
            }
        });
    }
}
