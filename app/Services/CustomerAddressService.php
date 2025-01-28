<?php
// app/Services/CustomerAddressService.php
namespace App\Services;

use App\Models\CustomerAddress;

class CustomerAddressService
{
    public function getUserAddresses($customer)
    {
        return $customer->addresses()->get();
    }

    public function createCustomerAddress($validatedData)
    {
        return CustomerAddress::create($validatedData);
    }

    public function updateCustomerAddress(CustomerAddress $customerAddress, array $data)
    {
        $customerAddress->update($data);
        return $customerAddress;
    }

    public function deleteCustomerAddress(CustomerAddress $customerAddress)
    {
        $customerAddress->delete();
    }
}
