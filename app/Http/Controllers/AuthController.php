<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    private $jwtKey;

    public function __construct()
    {
        $this->jwtKey = env('JWT_SECRET', '');
    }

    //User Registration with JWT Token issues=r
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate JWT token for the registered user
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => PHP_INT_MAX // Expiration time
        ];

        $token = JWT::encode($payload, $this->jwtKey, 'HS256');

        return response()->json([
            'Message' => "{$user->name}, successfully created.",
            'Token' => $token
        ], 201);
    }

    // User Login with JWT Token
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Generate JWT token for the registered user
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => PHP_INT_MAX
        ];

        $token = JWT::encode($payload, $this->jwtKey, 'HS256');

        return response()->json([
            'Message' => "Welcome, {$user->name}!",
        ]);
    }
}