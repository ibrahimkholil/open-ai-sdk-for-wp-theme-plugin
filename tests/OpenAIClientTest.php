<?php

namespace OpenAI\Tests;

use OpenAI\OpenAIClient;
use OpenAI\OpenAIConfig;
use OpenAI\OpenAIException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;

/**
 * Class OpenAIClientTest
 * Tests the OpenAIClient class.
 */
class OpenAIClientTest extends TestCase {
    private $client;
    private $config;

    protected function setUp(): void {
        // Initialize the OpenAIConfig with a dummy API key
        $this->config = new OpenAIConfig('test-api-key');

        // Create an instance of OpenAIClient
        $this->client = new OpenAIClient($this->config);
    }

    public function testChatSuccess() {
        // Mock the Guzzle client to simulate a successful API response
        $mockResponse = new Response(200, [], json_encode([
            'id' => 'chatcmpl-1',
            'object' => 'chat.completion',
            'created' => 123456789,
            'model' => 'gpt-3.5-turbo',
            'choices' => [[
                'message' => [
                    'role' => 'assistant',
                    'content' => 'This is a response from the assistant.'
                ],
                'finish_reason' => 'stop',
                'index' => 0,
            ]],
            'usage' => [
                'prompt_tokens' => 5,
                'completion_tokens' => 10,
                'total_tokens' => 15,
                'total_cost' => 0.03
            ]
        ]));

        // Create a mock for the HTTP client
        $mockClient = $this->createMock(Client::class);
        $mockClient->method('request')->willReturn($mockResponse);

        // Replace the HTTP client in the OpenAIClient with the mock client
        $this->client->setHttpClient($mockClient);

        // Prepare messages for chat
        $messages = [
            ['role' => 'user', 'content' => 'Hello!'],
        ];

        // Call the chat method
        $response = $this->client->chat('gpt-3.5-turbo', $messages, 100);

        // Assert the response structure
        $this->assertArrayHasKey('choices', $response);
        $this->assertEquals('This is a response from the assistant.', $response['choices'][0]['message']['content']);

        // Assert usage cost checking (verify that no exception is thrown)
        $this->client->checkCost($response['usage']);
    }

    public function testChatFailure() {
        // Simulate an API request failure
        $this->expectException(OpenAIException::class);
        $this->expectExceptionMessage('Error: Invalid API key');

        // Create a mock response for an error
        $mockResponse = new Response(401, [], json_encode([
            'error' => ['message' => 'Invalid API key']
        ]));

        // Create a mock for the HTTP client
        $mockClient = $this->createMock(Client::class);
        $mockClient->method('request')->willThrowException(new RequestException(
            'Error Communicating with Server',
            null,
            $mockResponse
        ));

        // Replace the HTTP client in the OpenAIClient with the mock client
        $this->client->setHttpClient($mockClient);

        // Prepare messages for chat
        $messages = [
            ['role' => 'user', 'content' => 'Hello!'],
        ];

        // Call the chat method which should throw an exception
        $this->client->chat('gpt-3.5-turbo', $messages, 100);
    }
}
