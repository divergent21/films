<?php

namespace Divergent\Films\Core\Router;

final class URL {

    private string $url;

    public function __construct (string $url) {
        $this->url = rtrim(explode('?', $url)[0], " \t\n\r\0\x0B/");

        $this->_validate_url();
    }

    public function get_url (): string {
        return $this->url;
    }

    private function _validate_url (): void {
        $url = trim($this->_without_variables());

        if (empty($url)) return;

        if (! empty($url) && strpos($url, '/') !== false) {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . $url;

            if (filter_var($url, FILTER_VALIDATE_URL)) return;
        }

        throw new \Exception('Invalid URL. ' . $url);
    }

    public function regex (): string {
        return join('/', array_map(function (string $part) {
            if (self::_url_part_is_variable($part)) {
                return '([^/]+?)';
            }

            return $part;
        }, explode('/', $this->url)));
    }

    private function _without_variables (): string {
        return join('/', array_filter(
            explode('/', $this->url),
            function (string $part): bool {
                return ! self::_url_part_is_variable($part);
            }
        ));
    }

    private static function _url_part_is_variable (string $part): bool {
        return preg_match('#^\{.+\}$#si', $part);
    }
}