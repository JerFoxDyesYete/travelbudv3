<?php

namespace App\Services;

use GuzzleHttp\Client; // Import GuzzleHttp\Client for making HTTP requests
use GuzzleHttp\Exception\RequestException; // Import RequestException from GuzzleHttp for handling request errors

class TranslationService
{
    protected $client; // Guzzle HTTP client instance
    protected $host; // API host for translation service
    protected $key; // API key for authentication

    public function __construct()
    {
        // Initialize Guzzle HTTP client
        $this->client = new Client();

        // Retrieve API host and key from environment variables
        $this->host = env('RAPIDAPI_HOST', 'google-translator9.p.rapidapi.com');
        $this->key = env('RAPIDAPI_KEY', '578df431femsh23e44eaa243f4cfp13e176jsn29a2901d6214');
    }

    /**
     * Detect the language of the provided text.
     *
     * @param string $text Text to detect language from
     * @return array Response from the API (detected language details)
     */
    public function detectLanguage($text)
    {
        try {
            // Send POST request to detect language endpoint
            $response = $this->client->request('POST', 'https://' . $this->host . '/v2/detect', [
                'body' => json_encode(['q' => $text]),
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                    'Content-Type' => 'application/json',
                ],
            ]);

            // Decode JSON response into associative array
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            // Handle request exception (e.g., connection errors, API errors)
            return [
                'error' => $e->getMessage(),
                'response' => $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null,
            ];
        }
    }

    /**
     * Get supported languages for translation.
     *
     * @return array Response from the API (list of supported languages)
     */
    public function getLanguages()
    {
        try {
            // Send GET request to languages endpoint
            $response = $this->client->request('GET', 'https://' . $this->host . '/v2/languages', [
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                ],
            ]);

            // Decode JSON response into associative array
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            // Handle request exception (e.g., connection errors, API errors)
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Translate text from source language to target language.
     *
     * @param string $text Text to translate
     * @param string $source Source language code (e.g., 'en' for English)
     * @param string $target Target language code (e.g., 'fr' for French)
     * @param string $format Format of the text ('text' or 'html')
     * @return array Response from the API (translated text)
     */
    public function translate($text, $source, $target, $format = 'text')
    {
        try {
            // Send POST request to translation endpoint
            $response = $this->client->request('POST', 'https://' . $this->host . '/v2', [
                'body' => json_encode([
                    'q' => $text,
                    'source' => $source,
                    'target' => $target,
                    'format' => $format,
                ]),
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                    'Content-Type' => 'application/json',
                ],
            ]);

            // Decode JSON response into associative array
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            // Handle request exception (e.g., connection errors, API errors)
            return [
                'error' => $e->getMessage(),
                'response' => $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null,
            ];
        }
    }
}
