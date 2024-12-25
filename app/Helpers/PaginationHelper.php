<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginationHelper
{
    /**
     * Get the name of the model.
     *
     * @param Model $model
     * @return string
     */
    protected static function getModelName(Model $model): string
    {
        return class_basename($model); // Get the model name without the namespace
    }

    public static function paginateResponse($originData, string $resourceClass, string $modelClass)
    {
        $name = static::getModelName(new $modelClass()); // Instantiate model to get the name
        $resourceCollection = new $resourceClass([]); // Assuming $ad is your model instance.
        // $resourceCollection = $resourceClass::collection($originData); // Create resource collection

        if (count($originData) > 0) {
            if ($originData instanceof \Illuminate\Pagination\LengthAwarePaginator && $originData->total() > $originData->perPage()) {
                $data = [
                    'records' => $resourceCollection::collection($originData),
                    'paginartion links' => [
                        'current page' => $originData->currentPage(),
                        'per page' => $originData->perPage(),
                        'total' => $originData->total(),
                        'links' => [
                            'first' => $originData->url(1),
                            'last' => $originData->url($originData->lastPage()),
                        ]
                    ]
                ];
            } else {
                $data = $resourceCollection::collection($originData);
            }
            return ApiResponse::sendResponse(code: 200, msg: 'Ads retrived successsfully Found', data: $data);
        }
        return ApiResponse::sendResponse(code: 200, msg: "$name Not Found", data: []);
    }
}
