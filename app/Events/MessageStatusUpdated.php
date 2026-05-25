<?php

namespace App\Events;

use App\Models\WhatsappMessage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;


class MessageStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(WhatsappMessage $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        Log::info('Broadcasting MessageStatusUpdated on: chat.' . $this->message->phone);
        return [
            new PrivateChannel('chat.' . $this->message->phone),
        ];
    }

    public function broadcastAs()
    {
        return 'MessageStatusUpdated';
    }
}
