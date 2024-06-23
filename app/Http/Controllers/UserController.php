<?php
// Starts the PHP code block.

namespace App\Http\Controllers;
// Specifies the namespace for the current class to avoid naming conflicts and organize code.

use App\Models\User;
// Imports the User model from the App\Models namespace for database operations related to users.

use Illuminate\Http\Request;
// Imports the Request class from the Illuminate\Http namespace to handle HTTP request data.

use Illuminate\Support\Facades\Validator;
// Imports the Validator facade from the Illuminate\Support\Facades namespace to perform validation on data.

use Illuminate\Support\Facades\Hash;
// Imports the Hash facade from the Illuminate\Support\Facades namespace for hashing passwords.

use Firebase\JWT\JWT;
// Imports the JWT class from the Firebase\JWT namespace for creating and decoding JSON Web Tokens.

class UserController extends Controller
// Defines the UserController class, which extends the base Controller class.
// This class handles CRUD operations for user management.

{
    private $jwtKey;
    // Declares a private property $jwtKey to store the JWT secret key.

    public function __construct()
    // Constructor method for the UserController class.
    {
        $this->jwtKey = env('JWT_SECRET', '');
        // Retrieves the JWT secret key from the environment variables and assigns it to $jwtKey.
        // Defaults to an empty string if JWT_SECRET is not set.
    }

    // Show all users
    public function index()
    // Method to retrieve and return a list of all users.
    {
        $users = User::all();
        // Fetches all user records from the database.

        return response()->json($users);
        // Returns a JSON response with the list of users.
    }

    // Create new user with JWT token issuance
    public function store(Request $request)
    // Method to create a new user and issue a JWT token.
    {
        $rules = [
        // Defines validation rules for the incoming request.

            'name' => 'required',
            // The 'name' field is required.

            'email' => 'required|email|unique:users,email',
            // The 'email' field is required, must be a valid email format, and must be unique in the 'users' table.

            'password' => 'required|min:6',
            // The 'password' field is required and must be at least 6 characters long.
        ];

        $validator = Validator::make($request->all(), $rules);
        // Creates a validator instance to check the request data against the defined rules.

        if ($validator->fails()) {
        // Checks if the validation failed.

            return response()->json(['errors' => $validator->errors()], 422);
            // Returns a JSON response with the validation errors and a 422 Unprocessable Entity status code.
        }

        $user = User::create([
        // Creates a new user in the database with the provided input.

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

            'exp' => time() + 60*60*24
            // Expiration timestamp, set to 24 hours from the current time.
        ];

        $token = JWT::encode($payload, $this->jwtKey, 'HS256');
        // Encodes the payload into a JWT token using the secret key and the HS256 algorithm.

        return response()->json([
        // Returns a JSON response containing a success message and the JWT token.

            'Message' => "{$user->name}, Successfully Created!",
            // Success message including the user's name.

            'Token' => $token,
            // The generated JWT token.
        ]);
    }

    // Get user by index
    public function show($id)
    // Method to retrieve and return a user by their ID.
    {
        $user = User::find($id);
        // Finds the user with the provided ID from the database.

        if (!$user) {
        // Checks if the user was not found.

            return response()->json(['Message' => "User with id {$id} not found."], 404);
            // Returns a JSON response with a message indicating the user was not found and a 404 Not Found status code.
        }

        return response()->json($user);
        // Returns a JSON response with the user's information.
    }

    // Update user's info by index
    public function update(Request $request, $id)
    // Method to update a user's information by their ID.
    {
        $user = User::find($id);
        // Finds the user with the provided ID from the database.

        if (!$user) {
        // Checks if the user was not found.

            return response()->json(['Message' => "User with id {$id} not found."], 404);
            // Returns a JSON response with a message indicating the user was not found and a 404 Not Found status code.
        }

        $user->update($request->all());
        // Updates the user's information with the provided input.

        return response()->json($user, 200);
        // Returns a JSON response with the updated user's information and a 200 OK status code.
    }

    // Delete user by index
    public function destroy($id)
    // Method to delete a user by their ID.
    {
        $user = User::find($id);
        // Finds the user with the provided ID from the database.

        if (!$user) {
        // Checks if the user was not found.

            return response()->json(['Message' => "User with id {$id} not found."], 404);
            // Returns a JSON response with a message indicating the user was not found and a 404 Not Found status code.
        }

        $user->delete();
        // Deletes the user from the database.

        return response()->json(['Message' => "User with id {$id} successfully deleted!"], 200);
        // Returns a JSON response with a message indicating the user was successfully deleted and a 200 OK status code.
    }
}
