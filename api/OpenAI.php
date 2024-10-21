<?php

namespace OpenAI;

/**
 * Class OpenAI
 * Main entry point for the OpenAI SDK.
 */
class OpenAI {
    private $client;

    /**
     * Constructor for OpenAI.
     *
     * @param string $apiKey The API key for OpenAI.
     * @throws \InvalidArgumentException If API key is empty.
     */
    public function __construct($apiKey) {
        $config = new OpenAIConfig($apiKey);
        $this->client = new OpenAIClient($config);
    }

    /**
     * Sends a request to the OpenAI API.
     *
     * @param string $endpoint The API endpoint to call.
     * @param string $method The HTTP method (GET, POST, etc.).
     * @param array $data The data to send with the request.
     * @return array The API response.
     * @throws OpenAIException If an error occurs during the request.
     */
    public function request($endpoint, $method = 'GET', $data = []) {
        return $this->client->request($endpoint, $method, $data);
    }
}
