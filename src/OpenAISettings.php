<?php

namespace OpenAI;

class OpenAISettings {

    const OPTION_NAME = 'openai_api_key';

    public function __construct() {
        // Add hooks to initialize settings and display the settings page
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    /**
     * Add the settings page to the WordPress admin menu
     */
    public function add_settings_page() {
        add_options_page(
            'OpenAI API Settings',  // Page title
            'OpenAI API',           // Menu title
            'manage_options',       // Capability
            'openai-settings',      // Menu slug
            [$this, 'display_settings_page'] // Callback to display the page
        );
    }

    /**
     * Register settings, sections, and fields
     */
    public function register_settings() {
        // Register the API key setting
        register_setting('openai_settings_group', self::OPTION_NAME);

        // Add a section to the settings page
        add_settings_section(
            'openai_settings_section',        // Section ID
            'OpenAI API Configuration',       // Section title
            null,                             // Optional callback for description
            'openai-settings'                 // Page on which to display
        );

        // Add the API key field
        add_settings_field(
            'openai_api_key',                 // Field ID
            'API Key',                        // Field title
            [$this, 'api_key_field_callback'], // Callback to display the field
            'openai-settings',                // Page on which to display
            'openai_settings_section'         // Section ID
        );
    }

    /**
     * Display the settings page
     */
    public function display_settings_page() {
        ?>
        <div class="wrap">
            <h1>OpenAI API Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('openai_settings_group'); // Settings group name
                do_settings_sections('openai-settings');  // Page slug
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Callback to display the API key input field
     */
    public function api_key_field_callback() {
        $api_key = get_option(self::OPTION_NAME, '');
        echo '<input type="text" id="openai_api_key" name="openai_api_key" value="' . esc_attr($api_key) . '" class="regular-text">';
        echo '<p class="description">Enter your OpenAI API key here.</p>';
    }

    /**
     * Retrieve the API key from the settings
     */
    public static function get_api_key() {
        return get_option(self::OPTION_NAME, '');
    }
}
