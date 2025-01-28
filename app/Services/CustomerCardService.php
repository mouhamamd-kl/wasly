<?php
// app/Services/CustomerCardService.php
namespace App\Services;

use App\Models\CustomerCard;

class CustomerCardService
{
    public function getUserCards($customer)
    {
        return $customer->cards()->get();
    }

    public function createCustomerCard($validatedData)
    {
        return CustomerCard::create($validatedData);
    }

    public function updateCustomerCard(CustomerCard $customerCard, array $data)
    {
        $customerCard->update([
            'card_number' => encrypt($data['card_number']),
            'expiration_date' => $data['expiration_date'],
            'card_type' => $data['card_type'],
            'cvv' => encrypt($data['cvv']),
        ]);

        return $customerCard;
    }

    public function deleteCustomerCard(CustomerCard $customerCard)
    {
        $customerCard->delete();
    }
}
