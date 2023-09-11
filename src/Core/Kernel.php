<?php 

namespace Divergent\Films\Core;

use Divergent\Films\Core\Traits\Singleton;
use Divergent\Films\Core\Database\Database;
use Divergent\Films\Core\Router\Router;
use Divergent\Films\Core\Response\Response;
use Divergent\Films\Models\Auth;

/**
 * Make the global variable App.
 */
final class Kernel {
    use Singleton;

    /**
     * @var array $env Keeps all confiration settings from the .env file
     */
    private array $env;

    /**
     * @var Database $database Object of the database class.
     */
    private Database $database;

    /**
     * @var Router $router Object of the route class.
     */
    private Router $router;

    private function __construct () {
        // Set global variable.
        $GLOBALS['kernel'] = $this;

        $this->load_env();

        // Init routes.
        $this->router = Router::init();
        $this->router->load_routes();

        // Connect to DB.
        $this->connect_to_database();

        // Start main logic.
        $this->entry();
    }

    /**
     * The entry point.
     * 
     * @return void
     */
    private function entry (): void {
        session_start();

        // Auth user
        $auth_user = Auth::try_get_user();
        if (! is_null($auth_user)) {
            $_SESSION['auth'] = true;
            $_SESSION['user'] = $auth_user;
        }

        // Get result from router.
        $result = $this->router->handle(
            $_SERVER['REQUEST_URI'], 
            $_SERVER['REQUEST_METHOD']
        );

        if ($result instanceof Response) {
            $result->response();
        } else {
            $response = new Response;
            $response->code(200);
            $response->content($result);
            $response->response();
        }
    }

    /**
     * Getter for the router.
     * 
     * @return Router The router.
     */
    public function router (): Router {
        return $this->router;
    }

    /**
     * Getter for the database.
     * 
     * @return Database The database.
     */
    public function database (): Database {
        return $this->database;
    }

    /**
     * Getter for the ENV.
     * 
     * @param string $key A key of an env parameter.
     * 
     * @return mixed Returning the value if it exists or false if not.
     */
    public function get_env (string $key): mixed {
        return isset($this->env[$key]) 
            ? $this->env[$key]
            : false
        ;
    }

    /**
     * Loading and parsing the env file.
     * 
     * @return void
     */
    private function load_env (): void {
        if (! file_exists(MAIN_DIR . '/.env')) {
            throw new \Exception('Failed loading the env file.');
        }

        $this->env = parse_ini_file(MAIN_DIR . '/.env');
    }

    /**
     * Connecting to the database.
     * 
     * @return void;
     */
    private function connect_to_database (): void {
        $this->database = Database::init();
        $this->database->connect(
            $this->env['DATABASE_HOST'],
            $this->env['DATABASE_USER'],
            $this->env['DATABASE_PASSWORD'],
            $this->env['DATABASE_NAME']
        );
    }
}