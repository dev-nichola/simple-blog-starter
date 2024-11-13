<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function register(Request $request) {
        $validate = Validator::make($request->all(),[
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:8|confirmed"
        ]);

        if($validate->fails()) {
            return $this->responseServer(200, [
                "statusCode" => 200,
                "message" => $validate->errors()
            ]);
        }

        $data = User::query()->create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(!$data) {
            $this->responseServer(409, [
                "statusCode" => 409,
                "ok" => false,
                "message" => "Gagal Membuat User Baru"
            ]);
        }

        $this->responseServer(201, [
            "ok" => true,
            "statusCode" => 201,
            "message" => $data
        ]);
    }
    public function login(Request $request) {
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
            "token" => $token,
            "statusCode" => 200,
            "ok" => true,
            "message" => [
                "user" => auth()->guard('api')->user(),
                "message" => "Sukses Login"
            ],
        ]);
    }
    public function logout() {
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        Auth::logout(true);

        if(!$removeToken) {
            return $this->responseServer(500, [
                "statusCode" => 500,
                "message" => "Token tidak di temukan"
            ]);
        }

        return $this->responseServer(200, [
            "statusCode" => 200,
            "message" => "Telah berhasil logout"
        ]);
    }
}
