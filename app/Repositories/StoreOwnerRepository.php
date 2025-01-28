<?php
// app/Repositories/StoreOwnerRepository.php
namespace App\Repositories;

use App\Models\StoreOwner;

class StoreOwnerRepository
{
    public function findById($id)
    {
        return StoreOwner::find($id);
    }

    public function getPaginated($perPage = 10)
    {
        return StoreOwner::latest()->paginate($perPage);
    }
}
