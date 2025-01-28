<?php
// app/Repositories/CustomerAddressRepository.php
namespace App\Repositories;

use App\Models\CustomerAddress;

class CustomerAddressRepository
{
    public function findById($id)
    {
        return CustomerAddress::findOrFail($id);
    }
}
