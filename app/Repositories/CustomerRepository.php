<?php
// app/Repositories/CustomerRepository.php
namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    public function findById($id)
    {
        return Customer::findOrFail($id);
    }
}
