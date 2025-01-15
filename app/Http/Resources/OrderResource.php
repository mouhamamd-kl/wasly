<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'store' => new StoreResource($this->whenLoaded('store')),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'delivery' => new DeliveryResource($this->whenLoaded('delivery')),
            'order_status' => new OrderStatusResource($this->whenLoaded('orderStatus')),
            'order_placed_at' => $this->order_placed_at,
            'order_delivered_at' => $this->order_delivered_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
