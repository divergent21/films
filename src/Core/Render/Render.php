<?php 

namespace Divergent\Films\Core\Render;

final class Render {

    const TEMPLATES_DIRECTORY = '/templates';

    /**
     * @var array $global_variables Array of variables for all views.
     */
    private static array $global_variables = [];

    private function __construct () {}

    public static function view (string $path, array $variables = []): string {
        if (strpos($path, '/') !== 0) {
            $path = '/' . $path;
        }

        $path = preg_replace('#^(^[\.]+)\.+#si', '$1', $path);

        $template_path = $_SERVER['DOCUMENT_ROOT'] . self::TEMPLATES_DIRECTORY . $path . '.php';

        if (! file_exists($template_path)) {
            throw new \Exception('Template "' . $path . '.php" is not exists.');
        }

        extract($variables);
        extract(self::$global_variables);

        ob_start();
        require_once $template_path;
        
        return ob_get_clean();
    }

    public static function set_global_variable (string $name, mixed $value): void {
        self::$global_variables[$name] = $value;
    }
}