<?php 

namespace Divergent\Films\Core\Router;

/**
 * Available request schema types.
 */
enum Types: string {
    const Get = 'GET';
    const Post = 'POST';
    const Put = 'PUT';
    const Patch = 'PATCH';
    const Delete = 'DELETE';

    public static function from_string (string $type): string {
        $type = strtoupper(trim($type));

        return match ($type) {
            'GET' => Self::Get,
            'POST' => Self::Post,
            'PUT' => Self::Put,
            'PATCH' => Self::Patch,
            'DELETE' => Self::Delete,
            default => throw new \Exception('Not available request method')
        };
    }
}