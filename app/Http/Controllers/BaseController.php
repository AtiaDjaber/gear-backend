<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public static function successData($result, $message)
    {
        $response = [
            "status" => true,
            "data" => $result,
            "message" => $message
        ];
        return response()->json($response, 200);
    }

    public static function errorData($message)
    {
        $response = [
            "status" => false,
            "data" => null,
            "message" => $message
        ];
        return response()->json($response, 204);
    }
}
