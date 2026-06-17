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
        'currency',
        'created_at',
        'updated_at',
        'lead_id',
        'team_id',
        'team_assigned_at',
        'marks',
        'offer',
        'referal',
        'failed_at'
    ];
    
     protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


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
        return $this->hasMany(Payment::class);
    }


    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'order_id', 'id');

    }


    public function ordercall()
    {
        return $this->hasmany(Ordercall::class, 'order_id', 'id')->with('user');
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

    public function additionals()
    {
        return $this->hasMany(\App\Models\Additional::class, 'order_id', 'order_id');
    }

    public function isInitiatedStatus(): bool
    {
        return strtolower(trim((string) $this->projectstatus)) === 'initiated';
    }

    public function assignTeamForInitiatedStatus(): void
    {
        if (!$this->isInitiatedStatus() || !is_null($this->team_id)) {
            return;
        }

        DB::transaction(function () {
            $order = self::whereKey($this->getKey())->lockForUpdate()->first();

            if (!$order || !$order->isInitiatedStatus() || !is_null($order->team_id)) {
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

            $totalAssignedToday = self::whereDate('updated_at', Carbon::today())
                ->whereRaw('LOWER(projectstatus) = ?', ['initiated'])
                ->whereNotNull('team_id')
                ->count();

            $allocations = [];
            $assignedCount = [];

            foreach ($teams as $team) {
                $allocations[$team->id] = floor(((float) $team->percentage / 100) * ($totalAssignedToday + 1));
                $assignedCount[$team->id] = self::whereDate('updated_at', Carbon::today())
                    ->whereRaw('LOWER(projectstatus) = ?', ['initiated'])
                    ->where('team_id', $team->id)
                    ->count();
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
