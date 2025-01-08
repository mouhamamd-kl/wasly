<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'photo' => $this->photo, // Assuming 'photo' is stored in the 'storage' directory
            'description' => $this->description,
            'stock_quantity' => $this->stock_quantity,
            'price' => $this->price,
            'is_active' => $this->is_active,
            'category_id' => $this->category_id, // Transform associated category if loaded
            'store_id' => $this->store_id, // Transform associated store if loaded
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
