<?php

namespace Divergent\Films\Core\Database;

use Divergent\Films\Core\Traits\Singleton;

final class Database {
    use Singleton;

    private ?object $connection;

    /**
     * Connecting to MySQL database.
     * Throw an error if connection was been failed.
     * 
     * @param string $host The database host.
     * @param string $user Username.
     * @param string $password Password.
     * @param string $database Database name.
     * 
     * @return object The database connection object.
     */
    public function connect (
        string $host = 'mysql-db',
        string $user = 'root',
        string $password = '',
        string $database = 'database'
    ): object {
        $this->connection = mysqli_connect($host, $user, $password, $database);

        if ($error = mysqli_connect_error()) {
            throw new \Exception('Failed connect with message: ' . $error);
        }

        // Prepare the Database of new connection.
        $this->prepare_database();

        return $this->connection;
    }

    /**
     * Running no ready migrations from the migrations folder.
     * 
     * @return void
     */
    private function prepare_database (): void {
        foreach (scandir(MAIN_DIR . '/migrations') as $migration) {
            if (in_array($migration, ['.', '..'])) continue;

            // .sql only
            if (! preg_match('#^.+\.sql$#si', $migration)) continue;

            // ignore ready
            if (preg_match('#^.+_ready\.sql$#si', $migration)) continue;

            $migration_sql = file_get_contents(MAIN_DIR . '/migrations/' . $migration);

            // explode by single commands
            foreach (explode(';', $migration_sql) as $sql_query) {
                $sql_query = trim($sql_query);

                if (empty($sql_query)) continue;

                // run single command
                $result = mysqli_query(
                    $this->get_connection(), 
                    $sql_query   
                );
    
                // check the result
                if ($result === false) {
                    throw new \Exception('Failed migrate "' . $migration . '".');
                }
            }

            // mark the migrations as ready
            rename(
                MAIN_DIR . '/migrations/' . $migration,
                MAIN_DIR . '/migrations/' . preg_replace('#^(.+)\.sql$#si', '$1_ready.sql', $migration)
            );
        }
    }

    /**
     * Getter for the DB connection.
     * 
     * @return ?object The DB connection.
     */
    public function get_connection (): ?object {
        return $this->connection;
    }

    /**
     * Make a query with the connection.
     * 
     * @param string $query The query.
     * 
     * @return \mysqli_result|bool Make
     */
    public function query (string $query): \mysqli_result|bool {
        return mysqli_query($this->connection, $query);
    }
}
