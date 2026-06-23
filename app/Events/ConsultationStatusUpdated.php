<?php

namespace App\Events;

use App\Models\Consultation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConsultationStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $consultation;

    /**
     * Create a new event instance.
     */
    public function __construct(Consultation $consultation)
    {
        $this->consultation = $consultation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('consultation.' . $this->consultation->patient->session_token),
        ];
    }
    
    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->consultation->id,
            'status' => $this->consultation->consultation_status,
            'status_label' => $this->consultation->status_label,
            'payment_status' => $this->consultation->transaction?->payment_status,
        ];
    }
}
