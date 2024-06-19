<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;

class UserController extends Controller
{
    private $jwtKey;

    public function __construct()
    {
        $this->jwtKey = env('JWT_SECRET', '');
    }

    // Show all users
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Create new user with JWT token issuance
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

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
            'exp' => time() + 60*60*24 // Expiration time (1 day)
        ];

        $token = JWT::encode($payload, $this->jwtKey, 'HS256');

        return response()->json([
            'Message' => "{$user->name}, Successfully Created!",
            'Token' => $token,
        ]);
    }

    // Get user by index
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['Message' => "User with id {$id} not found."], 404);
        }
        return response()->json($user);
    }

    // Update user's info by index
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['Message' => "User with id {$id} not found."], 404);
        }

        $user->update($request->all());
        return response()->json($user, 200);
    }

    // Delete user by index
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['Message' => "User with id {$id} not found."], 404);
        }
        $user->delete();
        return response()->json(['Message' => "User with id {$id} successfully deleted!"], 200);
    }
}
