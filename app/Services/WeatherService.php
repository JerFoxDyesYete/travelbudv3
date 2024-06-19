<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    private $client;
    private $host;
    private $apiKey;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://'. env('RAPIDAPI_WEATHER_HOST'),
            'timeout'  => 15, // Increased timeout
        ]);
        $this->host = env('RAPIDAPI_WEATHER_HOST');
        $this->apiKey = env('RAPIDAPI_WEATHER_KEY');
    }

    public function getCurrentWeather(string $location): array
    {
        return $this->request('GET', "/current.json", [
            'q' => $location
        ]);
    }

    public function getForecast(string $location, int $days = 3): array
    {
        return $this->request('GET', "/forecast.json", [
            'q' => $location,
            'days' => $days
        ]);
    }

    public function getHistory(string $location, string $date): array
    {
        return $this->request('GET', "/history.json", [
            'q' => $location,
            'dt' => $date
        ]);
    }

    private function request(string $method, string $uri, array $query = []): array
    {
        try {
            $response = $this->client->request($method, $uri, [
                'query' => $query,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->apiKey,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 'N/A';
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response';

            Log::error("WeatherService error: [{$statusCode}] " . $e->getMessage(), [
                'request' => $e->getRequest()->getUri(),
                'response' => $responseBody,
            ]);

            throw new \Exception("Unable to fetch weather data: {$responseBody}");
        }
    }
}
