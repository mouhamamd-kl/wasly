<?php

namespace App\Policies;

use App\Constants\Constants;
use App\Models\Product;
use App\Models\StoreOwner;
use App\Models\User;

class ProductPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function create(StoreOwner $user)
    {
        $isStoreOwner = $user->tokenCan(Constants::store_owner_guard);

        $isAdmin = auth('admin')->check();

        return $isStoreOwner || $isAdmin;
    }
    public function manage(StoreOwner $user, Product $product)
    {
        $isStoreOwner = $user->tokenCan(Constants::store_owner_guard) &&
            $user->id == $product->store->store_owner_id;

        $isAdmin = auth('admin')->check();

        return $isStoreOwner || $isAdmin;
    }
}
