<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // We use a public channel with an unguessable token because patients don't have standard Auth sessions
        return [
            new Channel('consultation.' . $this->message->consultation->patient->session_token),
        ];
    }
    
    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'sender_type' => $this->message->sender_type,
            'message' => $this->message->message,
            'file_path' => $this->message->attachment ? \Illuminate\Support\Facades\URL::signedRoute('files.attachment', $this->message->id) : null,
            'attachment_type' => $this->message->attachment_type,
            'created_at' => $this->message->created_at->format('H:i'),
        ];
    }
}
