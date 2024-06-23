<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT; // Import JWT from Firebase\JWT namespace
use Firebase\JWT\Key; // Import Key from Firebase\JWT namespace
use Illuminate\Http\Request;
use App\Models\User; // Import your User model here
use Illuminate\Support\Facades\Auth; // Import the Auth facade

class Authenticate
{
    // The handle method processes the incoming request.
    public function handle(Request $request, Closure $next)
    {
        // Check if the request is to the registration endpoint
        if ($request->is('register')) {
            // Skip authentication for registration endpoint
            return $next($request);
        }

        // Retrieve the Authorization header from the request
        $token = $request->header('Authorization');

        // Check if the token is not provided
        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        try {
            // Decode the JWT token using the JWT library and the JWT_SECRET environment variable
            $credentials = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        } catch (\Exception $e) {
            // Handle any exceptions thrown during token decoding
            return response()->json(['message' => 'Token is invalid'], 401);
        }

        // Check if the user exists in the database based on the sub claim (subject) of the JWT payload
        $user = User::find($credentials->sub);

        // If user does not exist, return an unauthorized response
        if (!$user) {
            return response()->json(['message' => 'User not found'], 401);
        }

        // Set the authenticated user using the Auth facade
        Auth::setUser($user);

        // Continue processing the request with the authenticated user
        return $next($request);
    }
}

