<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLog;

class LogUserActivity
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Define the routes you want to track
        $trackedRoutes = [
            // Weather APIs
            '/weather/current',
            '/weather/forecast',
            '/weather/history',

            // Translation APIs
            '/translation/detect',
            '/translation/languages',
            '/translation/translate',

            // Geocoding APIs
            '/geocoding/by-lat-lng',
            '/geocoding/by-place-id',
            '/geocoding/by-address',
        ];

        // Check if the current route is in the list of tracked routes
        if (in_array($request->getPathInfo(), $trackedRoutes)) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    protected function logActivity($request, $response)
    {
        $log = new UserLog();

        // Set user ID if authenticated, otherwise use null for guest user
        $log->user_id = Auth::id() ?: null; // Use null for guest users

        $log->route = $request->getPathInfo();
        $log->request_result = $response->getContent(); // You might want to customize this based on your needs

        $log->save();
    }
}
