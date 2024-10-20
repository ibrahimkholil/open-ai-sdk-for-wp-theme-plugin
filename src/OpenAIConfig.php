<?php

namespace OpenAI;

class OpenAIConfig {
    private $apiKey;
    private $apiEndpoint = 'https://api.openai.com/v1/';

    /**
     * Constructor for OpenAIConfig.
     *
     * @param string $apiKey The API key for OpenAI.
     * @throws \InvalidArgumentException If API key is empty.
     */
    public function __construct($apiKey) {
        if (empty($apiKey)) {
            throw new \InvalidArgumentException("API key cannot be empty.");
        }
        $this->setApiKey($apiKey);
    }

    /**
     * Sets the API key.
     *
     * @param string $apiKey The API key for OpenAI.
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }

    /**
     * Gets the API key.
     *
     * @return string The API key.
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * Gets the API endpoint.
     *
     * @return string The API endpoint.
     */
    public function getApiEndpoint() {
        return $this->apiEndpoint;
    }
}
