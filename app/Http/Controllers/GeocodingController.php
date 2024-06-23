<?php

namespace App\Http\Controllers;
// Specifies the namespace for the current class. 
// Namespaces help organize code and avoid name conflicts.

use App\Services\GeocodingService;
// Imports the GeocodingService class from the App\Services namespace. 
// This service will be used to handle geocoding operations.

use Illuminate\Http\Request;
// Imports the Request class from the Illuminate\Http namespace.
// This class is used to handle HTTP request data.

use Illuminate\Http\JsonResponse;
// Imports the JsonResponse class from the Illuminate\Http namespace.
// This class is used to create responses in JSON format.

class GeocodingController extends Controller
// Defines the GeocodingController class, which extends the base Controller class. 
// This class will handle HTTP requests related to geocoding.

{
    private $geocodingService;
    // Declares a private property named $geocodingService to store an instance of GeocodingService.

    public function __construct(GeocodingService $geocodingService)
    // Constructor method for the GeocodingController class.
    // It takes an instance of GeocodingService as a parameter and assigns it to the $geocodingService property.

    {
        $this->geocodingService = $geocodingService;
        // Assigns the provided GeocodingService instance to the $geocodingService property of the controller.
    }

    public function getByLatLng(Request $request): JsonResponse
    // Method to handle HTTP requests for geocoding by latitude and longitude.
    // It accepts a Request object and returns a JsonResponse.

    {
        $lat = $request->query('lat');
        // Retrieves the 'lat' query parameter from the request. 
        // This represents the latitude value.

        $lng = $request->query('lng');
        // Retrieves the 'lng' query parameter from the request. 
        // This represents the longitude value.

        if (!$lat || !$lng) {
        // Checks if either 'lat' or 'lng' is missing (null or false).
        // If either is missing, the following error response is returned.

            return response()->json(['error' => 'lat and lng parameter are required'], 400);
            // Returns a JSON response with an error message and a 400 Bad Request status code.
        }

        try {
        // Starts a try block to handle potential exceptions when calling the geocoding service.

            $data = $this->geocodingService->getByLatLng($lat, $lng);
            // Calls the getByLatLng method of the GeocodingService instance with the latitude and longitude.
            // Stores the resulting data in the $data variable.

            return response()->json($data, 200);
            // Returns the data in a JSON response with a 200 OK status code.
        } catch (\Exception $e) {
        // Catches any exceptions that occur during the geocoding service call.

            return response()->json(['error' => $e->getMessage()], 500);
            // Returns a JSON response with an error message and a 500 Internal Server Error status code.
        }
    }

    public function getByPlaceId(Request $request): JsonResponse
    // Method to handle HTTP requests for geocoding by place ID.
    // It accepts a Request object and returns a JsonResponse.

    {
        $placeId = $request->query('placeid');
        // Retrieves the 'placeid' query parameter from the request. 
        // This represents the place ID value.

        if (!$placeId) {
        // Checks if the 'placeid' parameter is missing (null or false).
        // If it is missing, the following error response is returned.

            return response()->json(['error' => 'placeid parameter is required'], 400);
            // Returns a JSON response with an error message and a 400 Bad Request status code.
        }

        try {
        // Starts a try block to handle potential exceptions when calling the geocoding service.

            $data = $this->geocodingService->getByPlaceId($placeId);
            // Calls the getByPlaceId method of the GeocodingService instance with the place ID.
            // Stores the resulting data in the $data variable.

            return response()->json($data, 200);
            // Returns the data in a JSON response with a 200 OK status code.
        } catch (\Exception $e) {
        // Catches any exceptions that occur during the geocoding service call.

            return response()->json(['error' => $e->getMessage()], 500);
            // Returns a JSON response with an error message and a 500 Internal Server Error status code.
        }
    }

    public function getByAddress(Request $request): JsonResponse
    // Method to handle HTTP requests for geocoding by address.
    // It accepts a Request object and returns a JsonResponse.

    {
        $address = $request->query('address');
        // Retrieves the 'address' query parameter from the request. 
        // This represents the address value.

        if (!$address) {
        // Checks if the 'address' parameter is missing (null or false).
        // If it is missing, the following error response is returned.

            return response()->json(['error' => 'address parameter is required'], 400);
            // Returns a JSON response with an error message and a 400 Bad Request status code.
        }

        try {
        // Starts a try block to handle potential exceptions when calling the geocoding service.

            $data = $this->geocodingService->getByAddress($address);
            // Calls the getByAddress method of the GeocodingService instance with the address.
            // Stores the resulting data in the $data variable.

            return response()->json($data, 200);
            // Returns the data in a JSON response with a 200 OK status code.
        } catch (\Exception $e) {
        // Catches any exceptions that occur during the geocoding service call.

            return response()->json(['error' => $e->getMessage()], 500);
            // Returns a JSON response with an error message and a 500 Internal Server Error status code.
        }
    }
}
