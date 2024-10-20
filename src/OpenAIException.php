<?php

namespace OpenAI;

/**
 * Class OpenAIException
 * Custom exception class for handling OpenAI API errors.
 */
class OpenAIException extends \Exception {
    /**
     * Constructor for OpenAIException.
     *
     * @param string $message The error message.
     * @param int $code The error code (default is 0).
     * @param \Throwable|null $previous The previous exception, if any (default is null).
     */
    public function __construct($message, $code = 0, \Throwable $previous = null) {
        // Call the parent constructor to initialize the Exception class
        parent::__construct($message, $code, $previous);
    }

    /**
     * Custom string representation of the exception.
     *
     * @return string The string representation of the exception.
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
