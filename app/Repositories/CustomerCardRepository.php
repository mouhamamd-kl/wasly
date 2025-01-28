<?php
// app/Repositories/CustomerCardRepository.php
namespace App\Repositories;

use App\Models\CustomerCard;

class CustomerCardRepository
{
    public function findById($id)
    {
        return CustomerCard::findOrFail($id);
    }
}

