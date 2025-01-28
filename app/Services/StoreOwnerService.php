<?php
namespace App\Services;

use App\Models\StoreOwner;
use Illuminate\Support\Facades\Hash;

class StoreOwnerService
{
    public function updateStoreOwnerData(StoreOwner $storeOwner, array $validatedData)
    {
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $storeOwner->update($validatedData);

        $changes = $storeOwner->getChanges();
        return [
            'changes' => $changes,
            'updatedFields' => implode(', ', array_diff(array_keys($changes), ['updated_at'])),
        ];
    }

    public function getLatestStoreOwners($limit = 10)
    {
        return StoreOwner::latest()->take($limit)->get();
    }

    public function searchStoreOwners(string $search = null)
    {
        return StoreOwner::query()
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->latest();
    }

    public function deleteStoreOwner(StoreOwner $storeOwner)
    {
        return $storeOwner->delete();
    }
}
