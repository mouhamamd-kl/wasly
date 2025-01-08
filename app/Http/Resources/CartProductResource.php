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
            'product_id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'photo' => $this->photo,
            'count' => $this->pivot->count,
            'subtotal' => $this->price * $this->pivot->count,
        ];
    }
}
