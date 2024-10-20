# OpenAI PHP SDK for WordPress

## Overview

The OpenAI PHP SDK allows developers to easily integrate OpenAI's API into their WordPress themes and plugins. This SDK simplifies the process of making API calls, handling responses, and managing errors.

## Installation

1. **Download the SDK**: Clone or download the repository.
2. **Include the SDK in your Plugin or Theme**:
   - Place the `openai-php-sdk` directory in your theme or plugin directory.

3. **Autoload Classes**:
   - In your main plugin file or theme's `functions.php`, include the SDK classes:
   ```php
   require_once 'path/to/openai-php-sdk/src/OpenAIClient.php';
   require_once 'path/to/openai-php-sdk/src/OpenAIConfig.php';
   require_once 'path/to/openai-php-sdk/src/OpenAIException.php';


## Example
Here’s a basic example of how to use the OpenAI SDK in your theme or plugin:

```php
add_action('init', function () {
    try {
        // Replace 'YOUR_API_KEY' with your actual API key
        $apiKey = 'YOUR_API_KEY';

        if (empty($apiKey)) {
            error_log('OpenAI Error: API key is not set.');
            return;
        }

        $config = new OpenAI\OpenAIConfig($apiKey);
        $client = new OpenAI\OpenAIClient($config);

        // Prepare a message to send to OpenAI
        $messages = [
            ['role' => 'user', 'content' => 'Hello, OpenAI!']
        ];

        // Make the API call
        $response = $client->chat('gpt-3.5-turbo', $messages, 50);

        // Log the response
        error_log('OpenAI Response: ' . print_r($response, true));

        // Handle cost management
        $client->checkCost($response['usage']);
        
    } catch (OpenAI\OpenAIException $e) {
        // Handle errors from the API
        error_log('OpenAI Error: ' . $e->getMessage());
    }
}); 
```

## Error Handling
The SDK automatically handles errors that may occur during the API call. Here’s how to catch exceptions and log them properly:

##Example of Error Handling
```php
try {
// Code to call OpenAI API
} catch (OpenAI\OpenAIException $e) {
// Log the error message for debugging
error_log('OpenAI Error: ' . $e->getMessage());

    // Optionally, display a user-friendly message or take other actions
    // echo 'There was an error communicating with the AI service. Please try again later.';
}
```
## Error Responses
When an error occurs, the OpenAIException will provide details about the issue. You can customize how you handle these errors based on your application's needs.

##Cost Management
To implement cost management, you can check the usage returned by the API response and log any costs that exceed your budget.

##Example of Cost Management
```php
public function checkCost($usage) {
    // Example logic: Log usage or notify if exceeding budget
    if (isset($usage['total_cost'])) {
        // Check if the total cost exceeds a defined threshold
        if ($usage['total_cost'] > 100) {
            // Log or alert the user
            error_log("Cost exceeded: " . $usage['total_cost']);
        }
    }
}

```
## Implementing Cost Checks
Call the checkCost method after receiving a response from the OpenAI API. This allows you to track and manage your spending effectively.

## License
This SDK is open-source and available under the MIT License.

## Support

### Additional Instructions

1. **Install Dependencies**:
   After creating your files, run the following command in your terminal from the root of your SDK directory to install dependencies:
   ```bash
   composer install

### Key Additions

1. **Error Handling Section**: This section explains how to catch exceptions, log error messages, and handle errors gracefully. It provides an example of how to implement this in practice.

2. **Cost Management Section**: This outlines how to monitor costs based on the API usage returned in responses. It includes a sample function that checks if the total cost exceeds a specified threshold and logs the cost.

3. **Practical Examples**: Examples are provided for error handling and cost management to clarify how to implement these features in a WordPress context.

### Final Steps

1. **Update Your Files**: Make sure to replace the `README.md` in your project with the updated content.
2. **Test the Implementation**: Ensure that the examples work as intended within your WordPress environment.
3. **Distribute the SDK**: Once everything is tested and confirmed to work, you can distribute the SDK to others, knowing that they have clear guidance on how to use it effectively.

If you have further adjustments or additions you'd like to make, feel free to ask!

## Use the SDK in Your Plugin or Theme:

Follow the usage examples in the README to integrate OpenAI functionality into your WordPress project.
## Testing:

You can add tests using PHPUnit by creating a tests directory and adding your test cases there.
## Deployment:

Make sure to keep your API keys secure and not expose them publicly.
Feel free to modify the content to suit your specific requirements or preferences! If you need further customization or additional features, just let me know!