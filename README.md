
# OpenAI SDK for WordPress

This is an OpenAI SDK for WordPress themes and plugins, allowing developers to integrate OpenAI's powerful GPT models into their WordPress site. This SDK provides a shortcode for generating AI content, as well as tools for handling API requests and managing costs.

## Features

- **Cost management**: Track API usage and manage costs using built-in utilities.
- **Error handling**: The SDK includes custom error handling for OpenAI API errors.
## Requirements

- PHP 7.4+
- WordPress 5.5+
- OpenAI API Key (obtainable from the [OpenAI website](https://beta.openai.com/signup/))
- Composer (for dependency management)
- Guzzle HTTP client (installed via Composer)

## Installation

1. **Download the SDK**:
   -Clone or download the SDK repository into your WordPress `wp-content/plugins` or `wp-content/themes` directory.

2. **Copy** `OpenAI` **folder and past it to your theme or plugin folder**:
3. **Create a <code>composer.json</code> File** : 

```bash
composer init
```
When prompted, provide details for your project (such as name, description, etc.), or just hit enter to use the default values. When asked for the dependencies, you can leave it empty and move forward.
4. **Require Guzzle:**
Now that you have a <code>composer.json</code> file, you can add Guzzle as a dependency by running the following command:
```bash
composer require guzzlehttp/guzzle
```
5. **Install Dependencies:**
After requiring Guzzle, Composer will automatically create the necessary <code>composer.json</code> and <code>composer.lock</code> files. Now, run:
```bash
composer install
```
6. **Include Composer Autoloader:**

In your theme’s functions.php file or your plugin’s main file, include the Composer autoloader so WordPress can load the SDK and its dependencies:
```bash
require_once __DIR__ . '/vendor/autoload.php';
```

`## Usage`

1. **Programmatic API Usage**:
   Developers can use the SDK programmatically by creating instances of the SDK's classes.

   Example:

   ```php
   use OpenAI\OpenAIClient;
   use OpenAI\OpenAIConfig;

   $config = new OpenAIConfig('your-api-key');
   $client = new OpenAIClient($config);
   
   $messages = [
       ['role' => 'user', 'content' => 'Write an article about WordPress development.']
   ];

   $response = $client->chat('gpt-4', $messages, 150);
   echo $response['choices'][0]['message']['content'];
   ```

## Error Handling

The SDK throws custom exceptions for API errors. You can catch and handle them like this:

```php
try {
    $response = $client->chat('gpt-4', $messages, 150);
} catch (OpenAI\OpenAIException $e) {
    echo 'OpenAI Error: ' . $e->getMessage();
}
```

## Cost Management

The SDK includes a basic cost management tool that tracks token usage from OpenAI API responses. You can configure custom alerts or track usage based on thresholds.

## Tests

Unit tests are included using PHPUnit. To run tests, simply run:

```bash
composer install
./vendor/bin/phpunit --testdox
```

## Contributing

Contributions are welcome! Please open an issue or submit a pull request for any improvements or bug fixes.

## License

This project is licensed under the MIT License.
