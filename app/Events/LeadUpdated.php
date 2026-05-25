<?php 
// app/Events/LeadUpdated.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Leads;

class LeadUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $id;
    public $project_title;

    public function __construct(Leads $lead)
    {
        $this->id = $lead->id;
        $this->project_title = $lead->project_title;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('leads'); // public channel
    }

    public function broadcastAs(): string
    {
        return 'LeadUpdated'; // event name on frontend
    }

    public function broadcastWith(): array
    {
        return [
            'leadId' => $this->id,
            'projectTitle' => $this->project_title,
        ];
    }
}
