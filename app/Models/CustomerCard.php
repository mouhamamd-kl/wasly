<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCard extends Model
{
    use HasFactory;
    public static function findOrFailWithResponse(int $id)
    {
        $customerCard = self::find($id);

        if (!$customerCard) {
            // Return the custom API response
            ApiResponse::sendResponse(404, 'Customer Card Not Found')->throwResponse();
        }
        return $customerCard;
    }
}
