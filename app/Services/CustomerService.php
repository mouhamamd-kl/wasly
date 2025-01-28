<?php
// app/Services/CustomerService.php
namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerService
{
    public function getAllCustomers($perPage = 10)
    {
        return Customer::latest()->paginate($perPage);
    }

    public function getLatestCustomers($limit = 10)
    {
        return Customer::latest()->take($limit)->get();
    }

    public function searchCustomers($searchTerm, $perPage = 10)
    {
        return Customer::when($searchTerm, function ($query, $searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        })->paginate($perPage);
    }

    public function updateCustomer(Customer $customer, array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if (!empty($data['photo'])) {
            $data['photo'] = profileImagePath($data['photo']);
        }

        $customer->update($data);

        return $customer;
    }

    public function deleteCustomer(Customer $customer)
    {
        $customer->delete();
    }
}
