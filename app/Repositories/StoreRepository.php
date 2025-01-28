<?php
namespace App\Repositories;

use App\Models\Store;

class StoreRepository
{
    public function findById($id)
    {
        return Store::find($id);
    }

    public function searchByName(string $name = null)
    {
        return Store::query()
            ->when($name, fn ($query) => $query->where('name', 'LIKE', "%{$name}%"));
    }
}
