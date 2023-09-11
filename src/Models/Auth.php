<?php 

namespace Divergent\Films\Models;

use Divergent\Films\Core\Database\Model;

final class Auth extends Model {

    /**
     * @var string $table_name The table name.
     */
    protected static string $table_name = 'Auth';

    /**
     * Insert new auth session.
     * 
     * @param int $user_id ID of the authorized user.
     * 
     * @return bool True - success. False - the session is already exist.
     */
    public static function insert (int $user_id): bool {
        global $kernel;

        if (self::check_isset_with_user_id($user_id)) return false;

        $kernel
            ->database()
            ->query(
                "INSERT INTO Auth (user_id, session_id) VALUES ('" . $user_id . "', '" . session_id() . "')"
            );

        return true;
    }

    /**
     * Check if the user is authorized.
     * 
     * @param int $user_id ID of the user.
     * 
     * @return bool
     */
    public static function check_isset_with_user_id (int $user_id): bool {
        global $kernel;

        $mysqli_result = $kernel
            ->database()
            ->query(
                "SELECT * FROM Auth WHERE user_id = '" . $user_id . "' AND session_id = '" . session_id() . "'"
            );

        return mysqli_num_rows($mysqli_result) > 0;
    }

    /**
     * Try to get the authorized user.
     * 
     * @return object|null The users object or null if not found.
     */
    public static function try_get_user (): object|null {
        global $kernel;

        $mysqli_result = $kernel
            ->database()
            ->query(
                "SELECT user_id FROM Auth WHERE session_id = '" . session_id() . "'"
            );

        // Get from Users by user_id
        if (mysqli_num_rows($mysqli_result) === 0) return null;

        return (object) [
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@films.ua'
        ];
    }
}