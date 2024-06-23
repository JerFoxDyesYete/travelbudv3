<?php

namespace App\Services;

use GuzzleHttp\Client; // Import GuzzleHttp\Client for making HTTP requests
use GuzzleHttp\Exception\RequestException; // Import RequestException from GuzzleHttp for handling request errors
use Illuminate\Support\Facades\Log; // Import Log facade for logging

class GeocodingService
{
    private $client; // Guzzle HTTP client instance
    private $apiKey; // API key for accessing the geocoding API

    public function __construct()
    {
        // Initialize Guzzle HTTP client with base URI and timeout
        $this->client = new Client([
            'base_uri' => 'https://map-geocoding.p.rapidapi.com',
            'timeout'  => 15, // Timeout in seconds
        ]);

        // Retrieve API key from environment variable
        $this->apiKey = env('GEOCODING_API_KEY'); // Store your API key in the .env file
    }

    /**
     * Get geocoding data by latitude and longitude.
     *
     * @param float $lat Latitude
     * @param float $lng Longitude
     * @return array Decoded JSON response
     * @throws \Exception If request fails
     */
    public function getByLatLng($lat, $lng)
    {
        return $this->request('GET', '/json', [
            'latlng' => "{$lat},{$lng}"
        ]);
    }

    /**
     * Get geocoding data by place ID.
     *
     * @param string $placeId Place ID
     * @return array Decoded JSON response
     * @throws \Exception If request fails
     */
    public function getByPlaceId($placeId)
    {
        return $this->request('GET', '/json', [
            'place_id' => $placeId
        ]);
    }

    /**
     * Get geocoding data by address.
     *
     * @param string $address Address
     * @return array Decoded JSON response
     * @throws \Exception If request fails
     */
    public function getByAddress($address)
    {
        return $this->request('GET', '/json', [
            'address' => $address
        ]);
    }

    /**
     * Perform an HTTP request to the geocoding API.
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $uri API endpoint URI
     * @param array $query Query parameters
     * @return array Decoded JSON response
     * @throws \Exception If request fails
     */
    private function request($method, $uri, $query)
    {
        try {
            // Send HTTP request using Guzzle HTTP client
            $response = $this->client->request($method, $uri, [
                'query' => $query,
                'headers' => [
                    'X-RapidAPI-Host' => 'map-geocoding.p.rapidapi.com',
                    'X-RapidAPI-Key' => $this->apiKey,
                ],
            ]);

            // Decode JSON response into associative array
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            // Handle request exception (e.g., connection errors, HTTP errors)
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 'N/A';
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response';

            // Log error details for troubleshooting
            Log::error("GeocodingService error: [{$statusCode}] " . $e->getMessage(), [
                'request' => $e->getRequest()->getUri(),
                'response' => $responseBody,
            ]);

            // Throw custom exception with error message
            throw new \Exception("Unable to fetch geocoding data: {$responseBody}");
        }
    }
}
