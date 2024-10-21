<?php
/*
Plugin Name: OpenAI PHP API TEST
Description: A PHP SDK for OpenAI API with error handling and cost management. Example for the plugin
Version: 1.0.0
Author: Ibrahim Khalil
Text Domain: openai-test
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

use OpenAI\OpenAIClient;
use OpenAI\OpenAIConfig;

class OpenAI_Test_Plugin {

    public function __construct() {
        $this->init_hooks();
        add_action('plugins_loaded', [$this, 'load_textdomain']);
    }

    // Load plugin text domain for translations
    public function load_textdomain() {
        load_plugin_textdomain('openai-test', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    private function init_hooks() {
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function register_admin_menu() {
        add_menu_page(
            __('OpenAI API Test', 'openai-test'), // Translatable menu title
            __('OpenAI API Test', 'openai-test'),
            'manage_options',
            'openai-test',
            [$this, 'display_plugin_page'],
            'dashicons-admin-site',
            90
        );
    }

    public function register_settings() {
        register_setting('openai-api-settings-group', 'openai_api_key', [
            'sanitize_callback' => 'sanitize_text_field' // Ensure input is sanitized
        ]);
    }

    public function display_plugin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('OpenAI API Settings', 'openai-api-test'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields('openai-api-settings-group'); ?>
                <?php do_settings_sections('openai-api-settings-group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('OpenAI API Key', 'openai-api-test'); ?></th>
                        <td><input type="text" name="openai_api_key" value="<?php echo esc_attr(get_option('openai_api_key')); ?>" style="width: 400px;" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>

            <?php
            // Validate the API key when it exists and was saved
            $api_key = get_option('openai_api_key');
            if (!empty($api_key)) {
                $valid_key = $this->validate_openai_api_key($api_key);
                if (!$valid_key) {
                    echo '<h3 style="color: red;">' . esc_html__('The OpenAI API key is invalid. Please enter a valid key.', 'openai-api-test') . '</h3>';
                } else {
                    echo '<h3 style="color: green;">' . esc_html__('The OpenAI API key is valid.', 'openai-api-test') . '</h3>';
                }
            }

            ?>
            <h2><?php esc_html_e('Test the OpenAI API', 'openai-api-test'); ?></h2>
            <form method="POST">
                <input type="hidden" name="openai_test_nonce" value="<?php echo esc_attr(wp_create_nonce('openai_test_action')); ?>" />
                <?php submit_button(__('Send Request to OpenAI', 'openai-api-test')); ?>
            </form>

            <?php
            if (isset($_POST['submit']) && isset($_POST['openai_test_nonce']) && wp_verify_nonce($_POST['openai_test_nonce'], 'openai_test_action')) {
                if (!empty($api_key)) {
                    $this->call_openai_api($api_key);
                } else {
                    echo '<h3 style="color: red;">' . esc_html__('Please set your OpenAI API key first.', 'openai-api-test') . '</h3>';
                }
            }
            ?>
        </div>
        <?php
    }

// Function to validate the OpenAI API key
    private function validate_openai_api_key($api_key) {
        try {
            // Initialize the OpenAI Client with the provided API key
            $config = new OpenAIConfig(sanitize_text_field($api_key));
            $client = new OpenAIClient($config);

            // Send a small test request
            $messages = [
                ['role' => 'system', 'content' => 'Check API key validity.']
            ];

            // Set a small token limit for this test
            $response = $client->chat('gpt-4o', $messages, 10);

            // Check for errors in the response
            if (isset($response['error'])) {
                return false; // API key is invalid
            }

            return true; // API key is valid

        } catch (Exception $e) {
            return false; // If an exception is thrown, the key is likely invalid
        }
    }


    private function call_openai_api($api_key) {
        // Initialize the OpenAI Client using the provided API key
        $config = new OpenAIConfig(sanitize_text_field($api_key));
        $client = new OpenAIClient($config);

        // Prepare the message for OpenAI
        $messages = [
            ['role' => 'user', 'content' => 'Write an article about WordPress development.']
        ];

        // Send the message to the OpenAI API
        try {
            $response = $client->chat('gpt-4o', $messages, 150);
            if (isset($response['choices'][0]['message']['content'])) {
                echo '<h3>' . esc_html__('Response from OpenAI:', 'openai-test') . '</h3>';
                echo '<pre>' . esc_html($response['choices'][0]['message']['content']) . '</pre>';
            } else {
                echo '<h3>' . esc_html__('No response received from OpenAI.', 'openai-test') . '</h3>';
            }
        } catch (Exception $e) {
            echo '<h3 style="color: red;">' . esc_html__('Error:', 'openai-test') . ' ' . esc_html($e->getMessage()) . '</h3>';
        }
    }
}

// Instantiate the plugin class
new OpenAI_Test_Plugin();
