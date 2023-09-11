<?php

namespace Divergent\Films\Core\Response;

use Divergent\Films\Core\Router\URL;

/**
 * 
 */
final class Response {

    /**
     * @var array AVAILABLE_CODES The list of available code for the response.
     */
    const AVAILABLE_CODES = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
    ];

    /**
     * @var int Response code
     */
    private int $code;

    /**
     * @var string Response content
     */
    private string $content;

    /**
     * @var string HTTP Content-Type header value.
     */
    private string $content_type;

    public function __construct () {
        // Setting default values
        $this->code(http_response_code());
        $this->content = '';
        $this->content_type = 'text/html';
    }

    /**
     * Getter/Setter of the response code.
     * 
     * @param int|null $code The respose status code. Default - null.
     * 
     * @return int|self Returing int when null was passed. Returning a self object when the code was been changed.
     */
    public function code (int $code = null): int|self {
        if ($code === null) return $this->code;

        if (! isset(self::AVAILABLE_CODES[$code])) {
            throw new \Exception('The response code "' . htmlentities($code) . '" is not available.');
        }

        $this->code = $code;

        return $this;
    }

    /**
     * Set the response content.
     * 
     * @param string $content The reponse content.
     * 
     * @return self Returning the self object.
     */
    public function content (?string $content = ''): self {
        if (! is_null($content)) {
            $content = trim($content);

            $this->content = $content;
        }

        return $this;
    }

    /**
     * Change the response content type to JSON and set the JSON content.
     * 
     * @param array|object $json JSON content.
     * 
     * @return self Returning the self object.
     */
    public function json (array|object $json = null): self {
        if (! is_null($json)) {
            $this->content(json_encode($json, JSON_UNESCAPED_UNICODE));

            $this->content_type = 'application/json';
        }

        return $this;
    }

    /**
     * Send response with all params.
     * 
     * @return void
     */
    public function response (): void {
        http_response_code($this->code);
        header('Content-Type: ' . $this->content_type);

        echo $this->content;

        exit;
    }

    /**
     * Putting the data to the session before response.
     * 
     * @param array $data The data that will be set.
     * @param bool $overwrite Overwrite if the key already exists in the session.
     * 
     * @return self
     */
    public function with (array $data, bool $overwrite = true): self {
        // Ignore empty data
        if (! empty($data)) {
            // Put the data
            $_SESSION = $overwrite 
                ? array_merge($_SESSION, $data)
                : array_merge($data, $_SESSION)
            ;
        }

        return $this;
    }

    /**
     * Redirecting to the URL with some HTTP code (default - 302).
     * 
     * @param string $url URL for the redirect.
     * @param int $code Redirect code.
     * 
     * @return void
     */
    public function redirect (string $url, int $code = 302): void {
        // Check URL
        $url = new URL($url);

        // Check code
        $this->code($code);

        // set location
        header('Location: ' . $url->get_url(), true, $this->code());

        exit;
    }
}