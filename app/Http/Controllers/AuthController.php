<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email:dns|unique:users',
            'phone_number' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'password' => 'required|min:8|max:255',
            'confirmation_password' => 'required|min:8|max:255'
        ];

        $input = $request->validate($rules);

        if ($input['password'] !== $input['confirmation_password']) {
            return response()->json([
                "status" => false,
                "message" => 'The password confirmation does not match.'
            ], 400);
        }

        $input['password'] = Hash::make($input['password']);

        $user = new User();
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->phone_number = $input['phone_number'];
        $user->password = $input['password'];
        $user->save();

        return response()->json([
            "status" => true,
            'message' => 'Registration successful! Please login.',
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $input = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($input)) {
            return response()->json([
                "status" => false,
                "message" => 'Email & Password is invalid!'
            ], 401);
        }

        $user = User::where('email', $input['email'])->first();

        return response()->json([
            "status" => true,
            'message' => 'Login successful!',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = auth()->user();
        $user->tokens()->delete();

        return response()->json([
            "status" => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
