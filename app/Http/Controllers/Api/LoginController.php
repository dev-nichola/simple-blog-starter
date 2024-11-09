<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __invoke(Request $request) {
        $validate = Validator::make($request->all(), [
            "email" => "required",
            "password" => "required"
        ]);

        if($validate->fails()) {
            return $this->responseServer(422, [
                "statusCode" => 422,
                "message" => $validate->errors(),
            ]);
        }

        $credentials = $request->only("email", "password");

        if(!$token = auth()->guard("api")->attempt(($credentials))) {
            return $this->responseServer(401, [
                "ok" => false,
                "statusCode" => 401,
                "message" => "email atau password anda salah"
            ]);
        }

        return $this->responseServer(200,[
            "statusCode" => 200,
            "ok" => true,
            "message" => [
                "user" => auth()->guard('api')->user(),
                "message" => "Sukses Login"
            ],
            "token", $token
        ]);
    }
}
