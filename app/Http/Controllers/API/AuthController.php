<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        try{
            $validation = Validator::make($request->all(), [
                "name" => "required",
                "email" => "required|email",
                "password" => "required",
                "confirm_password" => "required|same:password",
            ]);

            if($validation->fails()){
                return response()->json([
                    "status" => 0,
                    "message" => "Error in validating input",
                    "data" => $validation->errors()->all(),
                ]);
            }

            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
            ]);

            return response()->json([
                "status" => 1,
                "message" => "User Registered",
                "data" => $user
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                "status" => 0,
                "message" => "Registration Failed"
            ]);
        }
    }
}
