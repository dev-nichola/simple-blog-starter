<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __invoke(Request $request) {
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
}
