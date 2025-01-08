<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\CustomerCard;
use App\Models\User;

class CustomerCardPolicy
{
    /**
     * Determine whether the user can view the customer card.
     */
    public function view(Customer $customer, CustomerCard $customerCard): bool
    {
        return $customer->id === $customerCard->customer_id;
    }

    /**
     * Determine whether the user can update the customer card.
     */
    public function update(Customer $customer, CustomerCard $customerCard): bool
    {
        return $customer->id === $customerCard->customer_id;
    }

    /**
     * Determine whether the user can delete the customer card.
     */
    public function delete(Customer $customer, CustomerCard $customerCard): bool
    {
        return $customer->id === $customerCard->customer_id;
    }
}
