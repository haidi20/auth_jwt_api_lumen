<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>  app('auth')->factory()->getTTL() * 60
        ], 200);
    }

    protected function responseWithSuccess($data)
    {
        return response()->json([
            "status" => 200,
            "data" => $data,
        ], 200);
    }

    protected function responseWithError($data)
    {
        return response()->json([
            "status" => 500,
            "data" => $data,
        ], 500);
    }
}
