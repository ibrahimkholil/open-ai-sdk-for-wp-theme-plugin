<?php
/**
 * Plugin Name: OpenAI SDK
 * Description: An SDK for OpenAI integration in WordPress.
 * Version: 1.0.0
 * Author: Your Name
 */

// Autoload SDK classes
require_once plugin_dir_path(__FILE__) . 'src/OpenAIClient.php';
require_once plugin_dir_path(__FILE__) . 'src/OpenAIConfig.php';
require_once plugin_dir_path(__FILE__) . 'src/OpenAIException.php';
require_once plugin_dir_path(__FILE__) . 'src/OpenAISettings.php';

// Initialize the settings page for API key input
$settings = new OpenAI\OpenAISettings();

// Shortcode to generate an OpenAI article or response
add_shortcode('openai_article', function($atts) {
    // Extract shortcode attributes
    $atts = shortcode_atts(array(
        'model' => 'gpt-4o', // Default model
        'max_tokens' => 150, // Default maximum tokens
        'prompt' => 'Write an article about WordPress plugins.', // Default prompt
    ), $atts);

    try {
        $apiKey = OpenAI\OpenAISettings::get_api_key(); // Retrieve API key from settings

        if (empty($apiKey)) {
            return 'OpenAI API key is not set.';
        }

        $config = new OpenAI\OpenAIConfig($apiKey);
        $client = new OpenAI\OpenAIClient($config);

        // Prepare messages for the chat request
        $messages = [
            ['role' => 'user', 'content' => $atts['prompt']],
        ];

        // Call the chat method to get the response
        $response = $client->chat($atts['model'], $messages, intval($atts['max_tokens']));

        // Check for errors in the response
        if (isset($response['error'])) {
            return 'OpenAI Error: ' . $response['error']['message'];
        }

        // Return the generated content
        return nl2br(htmlspecialchars($response['choices'][0]['message']['content']));

    } catch (OpenAI\OpenAIException $e) {
        return 'OpenAI Error: ' . $e->getMessage();
    }
});

// Example usage for testing (can be removed later)
add_action('init', function () {
    // This is just for testing; you can remove it after confirming functionality
    $example_output = do_shortcode('[openai_article prompt="What are the benefits of using WordPress?"]');
    error_log('OpenAI Shortcode Output: ' . $example_output);
});
