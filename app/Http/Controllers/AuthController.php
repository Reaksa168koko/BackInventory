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
        // Validate input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6'],
            'role' => ['sometimes', 'string'], // optional field
        ]);

        // Create user
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role = $request->input('role', 'customer'); // default role
        $user->save();

        return response()->json([
            'message' => 'Register successful',
            'user' => $user
        ], 201);

    } catch (\Throwable $th) {
        return response()->json([
            'message' => $th->getMessage()
        ], 500);
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
