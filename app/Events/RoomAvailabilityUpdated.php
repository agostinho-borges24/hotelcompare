<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomAvailabilityUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Room $room) {}

    public function broadcastOn(): Channel
    {
        return new Channel('hotel.' . $this->room->hotel_id);
    }

    public function broadcastAs(): string
    {
        return 'room.availability';
    }

    public function broadcastWith(): array
    {
        return [
            'room_id'         => $this->room->id,
            'name'            => $this->room->name,
            'is_available'    => $this->room->is_available,
            'available_units' => $this->room->available_units,
            'total_units'     => $this->room->total_units,
        ];
    }
}
