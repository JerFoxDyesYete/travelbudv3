<?php
// Starts the PHP code block.

namespace App\Http\Controllers;
// Specifies the namespace for the current class to avoid naming conflicts and organize code.

use App\Models\User;
// Imports the User model from the App\Models namespace for database operations related to users.

use Illuminate\Http\Request;
// Imports the Request class from the Illuminate\Http namespace to handle HTTP request data.

use Illuminate\Support\Facades\Hash;
// Imports the Hash facade from the Illuminate\Support\Facades namespace for hashing passwords.

use Illuminate\Support\Facades\Auth;
// Imports the Auth facade from the Illuminate\Support\Facades namespace for authentication-related tasks.

use Firebase\JWT\JWT;
// Imports the JWT class from the Firebase\JWT namespace for creating and decoding JSON Web Tokens.

use Firebase\JWT\Key;
// Imports the Key class from the Firebase\JWT namespace for handling key operations in JWT.

class AuthController extends Controller
// Defines the AuthController class, which extends the base Controller class.
// This class handles user authentication-related actions.

{
    private $jwtKey;
    // Declares a private property $jwtKey to store the JWT secret key.

    public function __construct()
    // Constructor method for the AuthController class.
    {
        $this->jwtKey = env('JWT_SECRET', '');
        // Retrieves the JWT secret key from the environment variables and assigns it to $jwtKey.
        // Defaults to an empty string if JWT_SECRET is not set.
    }

    // User Registration with JWT Token
    public function register(Request $request)
    // Method to handle user registration.
    {
        $this->validate($request, [
        // Validates the incoming request to ensure it contains valid data.

            'name' => 'required|string|max:255',
            // The 'name' field is required, must be a string, and cannot exceed 255 characters.

            'email' => 'required|string|email|max:255|unique:users',
            // The 'email' field is required, must be a string, must be a valid email, cannot exceed 255 characters,
            // and must be unique in the 'users' table.

            'password' => 'required|string|min:6|confirmed',
            // The 'password' field is required, must be a string, must be at least 6 characters long,
            // and must match the 'password_confirmation' field.
        ]);

        $user = User::create([
        // Creates a new user in the database using the provided input.

            'name' => $request->name,
            // Sets the 'name' field of the new user.

            'email' => $request->email,
            // Sets the 'email' field of the new user.

            'password' => Hash::make($request->password),
            // Hashes the 'password' and sets it for the new user.
        ]);

        // Generate JWT token for the registered user
        $payload = [
        // Defines the payload for the JWT.

            'iss' => "lumen-jwt",
            // Issuer of the token, indicating the origin of the token.

            'sub' => $user->id,
            // Subject of the token, typically the user's unique identifier.

            'iat' => time(),
            // Issued At timestamp, indicating when the token was issued.

            'exp' => PHP_INT_MAX
            // Expiration timestamp, indicating when the token will expire.
            // In this case, it's set to the maximum integer value.
        ];

        $token = JWT::encode($payload, $this->jwtKey, 'HS256');
        // Encodes the payload into a JWT token using the secret key and the HS256 algorithm.

        return response()->json([
        // Returns a JSON response containing a success message and the JWT token.

            'Message' => "{$user->name}, successfully created.",
            // Success message including the user's name.

            'Token' => $token
            // The generated JWT token.
        ], 201);
        // The HTTP status code 201 indicates that a new resource has been created.
    }

    // User Login with JWT Token
    public function login(Request $request)
    // Method to handle user login.
    {
        $this->validate($request, [
        // Validates the incoming request to ensure it contains valid data.

            'email' => 'required|string|email',
            // The 'email' field is required, must be a string, and must be a valid email.

            'password' => 'required|string',
            // The 'password' field is required and must be a string.
        ]);

        $user = User::where('email', $request->email)->first();
        // Finds the first user with the provided email from the database.

        if (!$user || !Hash::check($request->password, $user->password)) {
        // Checks if the user does not exist or if the password is incorrect.

            return response()->json(['message' => 'Unauthorized'], 401);
            // Returns a JSON response with an 'Unauthorized' message and a 401 Unauthorized status code.
        }

        // Generate JWT token for the authenticated user
        $payload = [
        // Defines the payload for the JWT.

            'iss' => "lumen-jwt",
            // Issuer of the token, indicating the origin of the token.

            'sub' => $user->id,
            // Subject of the token, typically the user's unique identifier.

            'iat' => time(),
            // Issued At timestamp, indicating when the token was issued.

            'exp' => PHP_INT_MAX
            // Expiration timestamp, indicating when the token will expire.
            // In this case, it's set to the maximum integer value.
        ];

        $token = JWT::encode($payload, $this->jwtKey, 'HS256');
        // Encodes the payload into a JWT token using the secret key and the HS256 algorithm.

        return response()->json([
        // Returns a JSON response containing a welcome message and the JWT token.

            'Message' => "Welcome, {$user->name}!",
            // Welcome message including the user's name.

            'Token' => $token
            // The generated JWT token.
        ]);
    }
}
