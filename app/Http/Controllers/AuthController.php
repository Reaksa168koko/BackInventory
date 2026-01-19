<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
        public function register(Request $request)
        {
        try {
            $request->validate([
                "name"=>["required"],
                "email"=>["required","email","unique:users,email"],
                "password"=>["required","confirmed"],
                'role' => $request->input('role', 'customer'), 
            ]);
            $user = new User();
            $user ->name = request("name");
            $user ->email = request("email");
            $user ->password= Hash::make(request("password"));
            $user ->save();
            return response()->json([
                "Register" => $user
            ],201);

        } catch (\Throwable $th) {
            return response()->json([
                "message" =>$th->getMessage()
            ],500);
        }
    }


    public function login(Request $request)
{
    try {
        $credentials = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required"]
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                "message" => "Invalid email or password"
            ], 401);
        }

            $user = Auth::user();
            if ($user->role !== 'admin') {
            return response()->json([
                "message" => "You are not authorized to access dashboard"
            ], 403);
        }
    
        /** @var App\Models\User $user */
        $token = $user->createToken("email")->plainTextToken;
        return response()->json([
            "user" => $user,
            "token" => $token
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            "message" => $th->getMessage()
        ], 500);
    }
}
}
