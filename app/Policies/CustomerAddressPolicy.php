<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerAddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create a customer address.
     */
    public function create(Customer $customer)
    {
        // Only authenticated customers can create an address
        return true; // Allow any authenticated customer to create an address
    }

    /**
     * Determine whether the user can update the customer address.
     */
    public function update(Customer $customer, CustomerAddress $address)
    {
        // The user can update the address if they own the address
        return $customer->id === $address->customer_id;
    }

    /**
     * Determine whether the user can delete the customer address.
     */
    public function delete(Customer $customer, CustomerAddress $address)
    {
        // The user can delete their address if it's not the default address
        return $customer->id === $address->customer_id && !$address->is_default;
    }

    /**
     * Determine whether the user can view the customer address.
     */
    public function view(Customer $customer, CustomerAddress $address)
    {
        // The user can view the address if they own it
        return $customer->id === $address->customer_id;
    }

    /**
     * Determine whether the user can mark a customer address as default.
     */
    public function markAsDefault(Customer $customer, CustomerAddress $address)
    {
        // The user can only mark an address as default if it belongs to them
        // Also, ensure that only one address can be default at a time, which should be handled
        // by the controller or a service.
        return $customer->id === $address->customer_id;
    }
}
