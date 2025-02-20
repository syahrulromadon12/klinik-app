<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class ApiResponse
{
    public static function success($data = [], $message = "Request successful", $code = 200)
    {
        return response()->json([
            'request_id' => (string) Str::uuid(),
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function error($message = "Something went wrong", $errors = [], $code = 500)
    {
        \Log::error("ApiResponse::error called with status code: " . $code);
        
        return response()->json([
            'request_id' => (string) \Illuminate\Support\Str::uuid(),
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}
