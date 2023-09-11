<?php

namespace Divergent\Films\Core\Router;

use Divergent\Films\Core\Router\Types;
use Divergent\Films\Controllers\MainController;
use Divergent\Films\Core\Router\URL;
use Divergent\Films\Models\Auth;

use Divergent\Films\Core\Traits\Singleton;

/**
 * 
 */
final class Router {
    use Singleton;
    
    /**
     * @var array $routes Keeping all available routes.
     */
    private array $routes;

    /**
     * @var object 
     */
    private object $active_route;

    private function __construct () {
        // Init the routes array.
        $this->routes = [];

        // Setting default routes.
        $this->routes['404'] = [MainController::class, 'code_404'];
        $this->routes['403'] = function () {
            return (new \Divergent\Films\Core\Response\Response)->redirect('/login');
        };
    }

    /**
     * Load the routes
     * 
     * @return void
     */
    public function load_routes (): void {
        if (! empty($routes)) return;

        // load routes from the file
        require_once MAIN_DIR . '/routes.php';
    }

    /**
     * Handler request.
     * Match current URL with all registered routes.
     * 
     * If the route was found - checking by the reflection if the route handler params assets to URL data
     * If not - go to 404 handler
     * 
     * @param string $url Current URL.
     * @param string $type Type/Method of the request
     * 
     * @return mixed Re-return from the route handler.
     */
    public function handle (string $url, string $type): mixed {
        $url = new URL($url);
        $type = Types::from_string($type);

        foreach ($this->routes as $route => $types) {
            if (
                ! preg_match('#^' . $route . '$#si', $url->get_url(), $variables)
                || ! isset($types[$type])
            ) {
                continue;
            }

            $route = $types[$type];

            // Check requre auth
            if ($route['require_auth'] && (Auth::try_get_user() === null)) {
                // Forbien.
                return $this->routes['403']();
            }

            $variables = array_slice($variables, 1);
            $handler = $route['handler'];

            // Reflection
            $reflection_method = new \ReflectionMethod(join('::', $handler));
            foreach ($reflection_method->getParameters() as $index => $parameter) {
                $parameter_type = $parameter->getType();

                // NamedType parameters only.
                if (! $parameter_type instanceof \ReflectionNamedType) {
                    continue;
                }

                // If the URL does not have need parameter of the matched route handler.
                if (! isset($variables[$parameter->getPosition()])) {
                    
                    // If the parameter is optional - ignore
                    if ($parameter->isOptional()) {
                        continue;
                    }
                    
                    // If the parameter allows to be nullable - set it to null.
                    if ($parameter->allowsNull()) {
                        $variables[$parameter->getPosition()] = null;
                    }

                    // If the parameter has a default value - set it.
                    if ($parameter->isDefaultValueAvailable()) {
                        $variables[$parameter->getPosition()] = $parameter->getDefaultValue();
                    }
                }

                $parameter_type_name = $parameter_type->getName();

                if ($parameter_type_name == 'int' && ! is_numeric($variables[$index])) {
                    continue 2;
                }

                // Try to cast the data to the needle type.
                if (! settype($variables[$index], $parameter_type_name)) {
                    continue 2;
                }
            }

            $this->active_route = (object) [
                'method' => $type,
                'url' => $url->get_url(),
                'name' => $route['name']
            ];

            // Call the matched handler.
            return call_user_func_array($handler, $variables);
        }

        // Not found.
        return $this->routes['404']();
    }

    /**
     * Set a route.
     * 
     * @param string $url Route URL or URL pattern.
     * @param callable $handler Route handler.
     * @param string $method Route HTTP method.
     * 
     * @return void
     */
    public function set_route (
        string $url, 
        callable $handler, 
        string $method, 
        string $name = '',
        bool $require_auth = false
    ): void {
        $url = new URL($url);

        if (empty($name)) {
            $class_path = explode('\\', $handler[0]);
            $name = end($class_path) . '::' . $handler[1];
        }

        $this->routes[$url->regex()][$method] = compact(
            'handler', 
            'name',
            'require_auth'
        );
    }

    /**
     * Getter for the active route.
     * 
     * @return object The active route.
     */
    public function active_route (): object {
        return $this->active_route;
    }

    /**
     * Check the active route has some name
     * 
     * @return bool If the active route named by the name - true or false.
     */
    public function is_name (string $name): bool {
        return $this->active_route()->name === trim($name);
    }
}