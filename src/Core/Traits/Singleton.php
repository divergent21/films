<?php 

namespace Divergent\Films\Core\Traits;

/**
 * Provides the singleton pattern.
 * 
 * You can overwrite the construct method for custom behavior.
 */
trait Singleton {

    /**
     * @var ?object $instance Single instance of the class.
     */
    protected static ?object $instance = null;

    /**
     * Block creating object of a class.
     */
    private function __construct () {}

    /**
     * Block creating clones of an single instance of a class.
     */
    private function __clone () {}

    /**
     * Initialize a single instance of a class and returning it.
     * 
     * @return static Instance of a class
     */
    public static function init (): static {
        if (is_null(static::$instance)) {
            static::$instance = new Static;
        }

        return static::$instance;
    }
}