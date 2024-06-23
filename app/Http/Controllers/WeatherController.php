<?php

namespace App\Http\Controllers;
// Specifies the namespace for the current class.
// Namespaces help organize code and avoid name conflicts.

use App\Services\WeatherService;
// Imports the WeatherService class from the App\Services namespace.
// This service will be used to handle weather-related operations.

use Illuminate\Http\JsonResponse;
// Imports the JsonResponse class from the Illuminate\Http namespace.
// This class is used to create responses in JSON format.

use Illuminate\Http\Request;
// Imports the Request class from the Illuminate\Http namespace.
// This class is used to handle HTTP request data.

use Laravel\Lumen\Routing\Controller as BaseController;
// Imports the BaseController class from the Laravel\Lumen\Routing namespace.
// This is the base class for Lumen controllers.

class WeatherController extends BaseController
// Defines the WeatherController class, which extends the BaseController class.
// This class will handle HTTP requests related to weather information.

{
    private $weatherService;
    // Declares a private property named $weatherService to store an instance of WeatherService.
    // Private visibility restricts access to within this class only.

    public function __construct(WeatherService $weatherService)
    // Constructor method for the WeatherController class.
    // It takes an instance of WeatherService as a parameter and assigns it to the $weatherService property.

    {
        $this->weatherService = $weatherService;
        // Assigns the provided WeatherService instance to the $weatherService property of the controller.
    }

    public function getCurrentWeather(Request $request): JsonResponse
    // Method to handle HTTP requests for getting current weather information.
    // It accepts a Request object and returns a JsonResponse.

    {
        $this->validate($request, [
        // Uses the validate method to ensure the request contains valid data.

            'location' => 'required|string',
            // The 'location' parameter is required and must be a string.
        ], [
            'location.required' => 'The "location" parameter is required.',
            // Custom error message if 'location' is missing.

            'location.string' => 'The "location" parameter must be a string.',
            // Custom error message if 'location' is not a string.
        ]);

        $location = $request->input('location');
        // Retrieves the 'location' parameter from the request.
        // This represents the location for which the current weather is to be retrieved.

        try {
        // Starts a try block to handle potential exceptions when calling the weather service.

            $weather = $this->weatherService->getCurrentWeather($location);
            // Calls the getCurrentWeather method of the WeatherService instance with the location.
            // Stores the resulting weather data in the $weather variable.

            return response()->json($weather);
            // Returns the weather data in a JSON response.
        } catch (\Exception $e) {
        // Catches any exceptions that occur during the weather service call.

            return response()->json(['error' => $e->getMessage()], 500);
            // Returns a JSON response with an error message and a 500 Internal Server Error status code.
        }
    }

    public function getForecast(Request $request): JsonResponse
    // Method to handle HTTP requests for getting weather forecast information.
    // It accepts a Request object and returns a JsonResponse.

    {
        $this->validate($request, [
        // Uses the validate method to ensure the request contains valid data.

            'location' => 'required|string',
            // The 'location' parameter is required and must be a string.
        ], [
            'location.required' => 'The "location" parameter is required.',
            // Custom error message if 'location' is missing.

            'location.string' => 'The "location" parameter must be a string.',
            // Custom error message if 'location' is not a string.
        ]);

        $location = $request->input('location');
        // Retrieves the 'location' parameter from the request.
        // This represents the location for which the weather forecast is to be retrieved.

        try {
        // Starts a try block to handle potential exceptions when calling the weather service.

            $forecast = $this->weatherService->getForecast($location);
            // Calls the getForecast method of the WeatherService instance with the location.
            // Stores the resulting forecast data in the $forecast variable.

            return response()->json($forecast);
            // Returns the forecast data in a JSON response.
        } catch (\Exception $e) {
        // Catches any exceptions that occur during the weather service call.

            return response()->json(['error' => $e->getMessage()], 500);
            // Returns a JSON response with an error message and a 500 Internal Server Error status code.
        }
    }

    public function getHistory(Request $request): JsonResponse
    // Method to handle HTTP requests for getting historical weather information.
    // It accepts a Request object and returns a JsonResponse.

    {
        $this->validate($request, [
        // Uses the validate method to ensure the request contains valid data.

            'location' => 'required|string',
            // The 'location' parameter is required and must be a string.

            'date' => 'required|date_format:Y-m-d',
            // The 'date' parameter is required and must follow the 'YYYY-MM-DD' date format.
        ], [
            'location.required' => 'The "location" parameter is required.',
            // Custom error message if 'location' is missing.

            'location.string' => 'The "location" parameter must be a string.',
            // Custom error message if 'location' is not a string.

            'date.required' => 'The "date" parameter is required.',
            // Custom error message if 'date' is missing.

            'date.date_format' => 'The "date" parameter must be in YYYY-MM-DD format.',
            // Custom error message if 'date' does not follow the specified format.
        ]);

        $location = $request->input('location');
        // Retrieves the 'location' parameter from the request.
        // This represents the location for which the historical weather is to be retrieved.

        $date = $request->input('date');
        // Retrieves the 'date' parameter from the request.
        // This represents the date for which the historical weather is to be retrieved.

        try {
        // Starts a try block to handle potential exceptions when calling the weather service.

            $history = $this->weatherService->getHistory($location, $date);
            // Calls the getHistory method of the WeatherService instance with the location and date.
            // Stores the resulting historical weather data in the $history variable.

            return response()->json($history);
            // Returns the historical weather data in a JSON response.
        } catch (\Exception $e) {
        // Catches any exceptions that occur during the weather service call.

            return response()->json(['error' => $e->getMessage()], 500);
            // Returns a JSON response with an error message and a 500 Internal Server Error status code.
        }
    }
}
