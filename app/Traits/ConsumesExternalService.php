<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumesExternalService
{
    /**
     * Send a request to any service
     *
     * @param string $method
     * @param string $requestUrl
     * @param array $form_params
     * @param array $headers
     * @return string
     */
    public function performRequest($method, $requestUrl, $form_params = [], $headers = [])
    {
        // Create a new client request
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        if(isset($this->secret)) {
            $headers['Authorization'] = $this->secret;
        }

        // Perform the request (method, url, form parameters, headers)
        $response = $client->request($method, $requestUrl, [
            'form_params' => $form_params,
            'headers' => $headers
        ]);

        // Return the response body contents
        return $response->getBody()->getContents();
    }
}
