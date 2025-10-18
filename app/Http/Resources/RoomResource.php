<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'room_name' => $this->room_name,
            'location' => $this->location,
            'capacity' => $this->capacity,
            'description' => $this->description,
            'bookings' => BookingResource::collection($this->bookings),
        ];
    }
}
