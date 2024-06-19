<?php 
namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\User; // Import your User model here
use Illuminate\Support\Facades\Auth; // Import the Auth facade

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the request is to the registration endpoint
        if ($request->is('register')) {
            // Skip authentication for registration endpoint
            return $next($request);
        }

        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        try {
            $credentials = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token is invalid'], 401);
        }

        // Check if the user exists
        $user = User::find($credentials->sub);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 401);
        }

        // Set the authenticated user using the Auth facade
        Auth::setUser($user);

        return $next($request);
    }
}

