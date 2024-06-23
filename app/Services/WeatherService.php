<?php

namespace App\Services;

use GuzzleHttp\Client; // Import GuzzleHttp\Client for making HTTP requests
use GuzzleHttp\Exception\RequestException; // Import RequestException from GuzzleHttp for handling request errors
use Illuminate\Support\Facades\Log; // Import Log facade for logging

class WeatherService
{
    private $client; // Guzzle HTTP client instance
    private $host; // API host for weather service
    private $apiKey; // API key for authentication

    public function __construct()
    {
        // Initialize Guzzle HTTP client with base URI and timeout
        $this->client = new Client([
            'base_uri' => 'https://' . env('RAPIDAPI_WEATHER_HOST'),
            'timeout'  => 15, // Timeout in seconds
        ]);

        // Retrieve API host and key from environment variables
        $this->host = env('RAPIDAPI_WEATHER_HOST');
        $this->apiKey = env('RAPIDAPI_WEATHER_KEY');
    }

    /**
     * Get current weather data for a location.
     *
     * @param string $location Location for which to fetch weather data
     * @return array Decoded JSON response containing weather data
     * @throws \Exception If request fails
     */
    public function getCurrentWeather(string $location): array
    {
        return $this->request('GET', '/current.json', [
            'q' => $location
        ]);
    }

    /**
     * Get weather forecast data for a location.
     *
     * @param string $location Location for which to fetch weather forecast
     * @param int $days Number of days for forecast (default: 3 days)
     * @return array Decoded JSON response containing weather forecast data
     * @throws \Exception If request fails
     */
    public function getForecast(string $location, int $days = 3): array
    {
        return $this->request('GET', '/forecast.json', [
            'q' => $location,
            'days' => $days
        ]);
    }

    /**
     * Get historical weather data for a location on a specific date.
     *
     * @param string $location Location for which to fetch historical weather data
     * @param string $date Date for which to fetch historical weather data (YYYY-MM-DD format)
     * @return array Decoded JSON response containing historical weather data
     * @throws \Exception If request fails
     */
    public function getHistory(string $location, string $date): array
    {
        return $this->request('GET', '/history.json', [
            'q' => $location,
            'dt' => $date
        ]);
    }

    /**
     * Perform an HTTP request to the weather API.
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $uri API endpoint URI
     * @param array $query Query parameters
     * @return array Decoded JSON response
     * @throws \Exception If request fails
     */
    private function request(string $method, string $uri, array $query = []): array
    {
        try {
            // Send HTTP request using Guzzle HTTP client
            $response = $this->client->request($method, $uri, [
                'query' => $query,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->apiKey,
                ],
            ]);

            // Decode JSON response into associative array
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            // Handle request exception (e.g., connection errors, API errors)
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 'N/A';
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response';

            // Log error details for troubleshooting
            Log::error("WeatherService error: [{$statusCode}] " . $e->getMessage(), [
                'request' => $e->getRequest()->getUri(),
                'response' => $responseBody,
            ]);

            // Throw custom exception with error message
            throw new \Exception("Unable to fetch weather data: {$responseBody}");
        }
    }
}
