<?php

namespace App\Events;

use App\Models\EmailMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public EmailMessage $email)
    {
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('emails.account.'.$this->email->email_configuration_id)];
    }

    public function broadcastAs(): string
    {
        return 'email.received';
    }

    public function broadcastWith(): array
    {
        return ['id' => $this->email->id, 'thread_id' => $this->email->thread_id];
    }
}
