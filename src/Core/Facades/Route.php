<?php 

namespace Divergent\Films\Core\Facades;

use Divergent\Films\Core\Router\Types;

final class Route {
    
    /**
     * @var array AVAILABLE_METHODS Available methods for creating a route.
     */
    const AVAILABLE_METHODS = [
        'get', 
        'post', 
        'patch', 
        'put', 
        'delete'
    ];

    private function __construct () {}

    private function __clone () {}

    public static function __callStatic (string $name, array $arguments) {
        $name = strtolower(trim($name));

        if (
            in_array($name, self::AVAILABLE_METHODS) &&
            (count($arguments) >= 2 && count($arguments) <= 4) &&
            is_string($arguments[0]) &&
            is_callable($arguments[1])
        ) {
            global $kernel;

            $kernel
                ->router()
                ->set_route(
                    $arguments[0], 
                    $arguments[1], 
                    Types::from_string($name),
                    isset($arguments[2]) ? $arguments[2] : '',
                    isset($arguments[3]) ? $arguments[3] : false
                )
            ;
        }
    }
}