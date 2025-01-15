<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
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
            // Personal details
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,

            // Contact information
            'phone' => $this->phone,
            'email' => $this->email,

            // Account details
            'email_verified_at' => $this->email_verified_at,

            // Additional attributes
            'photo' => $this->photo,

            // Relationships
            'delivery_status' => new DeliveryStatusResource($this->whenLoaded('deliveryStatus')),

            // Location details
            'current_latitude' => $this->current_latitude,
            'current_longitude' => $this->current_longitude,
            
            'chat_id'=>$this->chat_id,
            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
