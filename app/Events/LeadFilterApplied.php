<?php 
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;

class LeadFilterApplied implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function broadcastOn()
    {
        return new Channel('lead-filters');
    }

    public function broadcastAs()
    {
        return 'lead-filter-applied';
    }

    public function broadcastWith()
    {
        return ['filters' => $this->filters];
    }
}
