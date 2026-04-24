<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                "success" => 0,
                "message" => "Invalid Credentials",
                "data" => null
            ], 401);
        }

        if (Auth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ])) {

            if ($user->status == "blocked") {
                $user->password_attempts = 0;
                $user->save();
                return response()->json([
                    "success" => 0,
                    "message" => "Your account has been blocked. Contact our team to resolve this issue",
                    "code" => "ACCOUNT_BLOCKED",
                    "data" => null,
                ], 403);
            }

            if ($user->status == "pending") {
                $user->password_attempts = 0;
                $user->save();
                return response()->json([
                    "success" => 0,
                    "message" => "Your account confirmation is pending",
                    "code" => "ACCOUNT_PENDING",
                    "data" => null,
                ], 403);
            }

            $user->password_attempts = 0;
            $user->save();

            $token = $user->createToken('hook-token')->plainTextToken;

            return response()->json([
                "success" => 1,
                "message" => "Login Successful",
                "token" => $token,
                "data" => $user,
            ]);
        }

        // Wrong password
        $user->password_attempts += 1;

        if ($user->password_attempts >= 5) {
            $user->status = "blocked";
        }

        $user->save();

        return response()->json([
            "success" => 0,
            "message" => "Invalid Credentials",
            "remaining_attempts" => max(0, 5 - $user->password_attempts),
            "data" => null
        ], 401);
    }
}
