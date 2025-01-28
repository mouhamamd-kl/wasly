<?php
// app/Services/StoreService.php
namespace App\Services;

use App\Models\Store;
use App\Models\OrderItem;

class StoreService
{
    public function getStoresByStatus(int $storeId, string $status)
    {
        $store = Store::findOrFail($storeId);
        return OrderItem::whereHas('order', fn($query) => $query->where('store_id', $store->id))
            ->whereHas('status', fn($query) => $query->where('name', $status))
            ->with([
                'order',
                'status',
                'product' => fn($query) => $query->withAvg('ratings as average_rating', 'rating')
                    ->with(['category', 'store'])
                    ->withCount(['reviews as reviews_count', 'orderItems']),
            ])
            ->get();
    }

    public function updateStoreData(Store $store, array $validatedData)
    {
        $store->update($validatedData);

        $changes = $store->getChanges();
        return [
            'changes' => $changes,
            'updatedFields' => implode(', ', array_diff(array_keys($changes), ['updated_at'])),
        ];
    }

    public function getNearbyStores($latitude, $longitude, $radius, $limit)
    {
        return Store::getNearby($latitude, $longitude, $radius, $limit)->get();
    }

    public function getPopularByOrders()
    {
        return Store::getPopularByOrders()->get();
    }

    public function getPopularByRatings()
    {
        return Store::getPopularByRatings()->get();
    }
}
