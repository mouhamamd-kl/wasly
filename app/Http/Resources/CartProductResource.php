<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'product' => ProductResource::collection($this->whenLoaded('product')),,
            'count' => $this->pivot->count,
            'subtotal' => $this->price * $this->pivot->count,
        ];
    }
}
