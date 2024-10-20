<?php

namespace OpenAI;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class OpenAIClient
 * Handles communication with the OpenAI API.
 */
class OpenAIClient {
    private $config;
    private $httpClient;

    /**
     * Constructor for OpenAIClient.
     *
     * @param OpenAIConfig $config Configuration object.
     */
    public function __construct(OpenAIConfig $config) {
        $this->config = $config;
        $this->httpClient = new Client();
    }

    /**
     * Set the HTTP client for testing purposes.
     *
     * @param Client $httpClient The Guzzle HTTP client.
     */
    public function setHttpClient(Client $httpClient) {
        $this->httpClient = $httpClient;
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
        try {
            $response = $this->httpClient->request($method, $this->config->getApiEndpoint() . $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->config->getApiKey(),
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            $this->handleError($e);
        }
    }

    /**
     * Handles errors from the API request.
     *
     * @param RequestException $e The exception thrown during the request.
     * @throws OpenAIException If an error occurs.
     */
    private function handleError(RequestException $e) {
        if ($e->hasResponse()) {
            $responseBody = json_decode($e->getResponse()->getBody(), true);
            throw new OpenAIException("Error: " . $responseBody['error']['message'], $e->getCode());
        } else {
            throw new OpenAIException("Network error: " . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Sends a chat request to the OpenAI API and handles the creation of chat completions.
     *
     * @param string $model The model to use (e.g., 'gpt-3.5-turbo').
     * @param array $messages The messages to send to the chat.
     * @param int $maxTokens The maximum number of tokens to generate.
     * @return array The API response.
     * @throws OpenAIException If an error occurs during the request.
     */
    public function chat($model, array $messages, $maxTokens) {
        $response = $this->request('chat/completions', 'POST', [
            'model' => $model,
            'messages' => $messages,
            'max_tokens' => intval($maxTokens),
        ]);

        // Optional: Check for and log usage or cost data if included in the response
        if (isset($response['usage'])) {
            $this->checkCost($response['usage']);
        }

        return $response;
    }

    /**
     * Checks usage costs based on the response.
     *
     * @param array $usage The usage data returned from the API.
     * @return void
     */
    public function checkCost($usage) {
        // Example logic: Log usage or notify if exceeding budget
        if (isset($usage['total_tokens'])) {
            $totalTokens = $usage['total_tokens'];

            // You can define your own threshold, for example, 1000 tokens
            if ($totalTokens > 1000) {
                error_log("Usage alert: Total tokens exceeded the threshold. Used: " . $totalTokens);
            }
        }
    }
}
