<?php

namespace App\Http\Middleware;

use Closure; // Import the Closure class for middleware handling
use Illuminate\Support\Facades\Auth; // Import the Auth facade for authentication utilities
use App\Models\UserLog; // Import the UserLog model for logging user activities

// This middleware logs user activities on specific routes.
class LogUserActivity
{
    // The handle method processes the request and response.
    public function handle($request, Closure $next)
    {
        // Pass the request to the next middleware or controller and capture the response.
        $response = $next($request);

        // Define an array of routes for which user activities should be tracked.
        $trackedRoutes = [
            '/weather/current',     // Current weather data route
            '/weather/forecast',    // Weather forecast route
            '/weather/history',     // Historical weather data route
            '/translation/detect',  // Language detection route
            '/translation/languages', // Supported languages route
            '/translation/translate', // Translation route
            '/geocoding/by-lat-lng',  // Geocoding by latitude and longitude route
            '/geocoding/by-place-id', // Geocoding by place ID route
            '/geocoding/by-address',  // Geocoding by address route
        ];

        // Check if the current request's route is in the list of tracked routes.
        if (in_array($request->getPathInfo(), $trackedRoutes)) {
            // Log the activity if the route is tracked.
            $this->logActivity($request, $response);
        }

        // Return the response to the client.
        return $response;
    }

    // The logActivity method logs the request and response data.
    protected function logActivity($request, $response)
    {
        // Create a new UserLog instance.
        $log = new UserLog();

        // Set the user_id to the ID of the authenticated user, or null if no user is authenticated.
        $log->user_id = Auth::id() ?: null;

        // Record the route being accessed.
        $log->route = $request->getPathInfo();

        // Store the content of the response. Customize this as needed.
        $log->request_result = $response->getContent();

        // Save the log entry to the database.
        $log->save();
    }
}
