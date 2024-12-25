<?php

namespace App\Helpers;

use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    static function sendResponse($code = 200, $msg = null, $data = null):JsonResponse
    {
        $response = [
            'status' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        return response()->json($response, $code);
    }
}
