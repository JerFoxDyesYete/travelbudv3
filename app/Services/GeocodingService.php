<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://map-geocoding.p.rapidapi.com',
            'timeout'  => 15, // Timeout in seconds
        ]);
        $this->apiKey = env('GEOCODING_API_KEY'); // Store your API key in the .env file
    }

    public function getByLatLng($lat, $lng)
    {
        return $this->request('GET', '/json', [
            'latlng' => "{$lat},{$lng}"
        ]);
    }

    public function getByPlaceId($placeId)
    {
        return $this->request('GET', '/json', [
            'place_id' => $placeId
        ]);
    }

    public function getByAddress($address)
    {
        return $this->request('GET', '/json', [
            'address' => $address
        ]);
    }

    private function request($method, $uri, $query)
    {
        try {
            $response = $this->client->request($method, $uri, [
                'query' => $query,
                'headers' => [
                    'X-RapidAPI-Host' => 'map-geocoding.p.rapidapi.com',
                    'X-RapidAPI-Key' => $this->apiKey,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 'N/A';
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response';

            Log::error("GeocodingService error: [{$statusCode}] " . $e->getMessage(), [
                'request' => $e->getRequest()->getUri(),
                'response' => $responseBody,
            ]);

            throw new \Exception("Unable to fetch geocoding data: {$responseBody}");
        }
    }
}
