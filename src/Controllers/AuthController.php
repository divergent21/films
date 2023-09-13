<?php 

namespace Divergent\Films\Controllers;

use Divergent\Films\Core\Render\Render;
use Divergent\Films\Core\Response\Response;

use Divergent\Films\Models\Auth;

final class AuthController {
    
    // Show the login form
    public static function login () {
        return Render::view('login');
    }

    // Login handler
    public static function signin () {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Check the data
        if (! self::check_auth($email, $password)) {

            // Redirect to the login form with an error.
            (new Response)->with([
                'errors' => [
                    'Failed auth. Check the data.'
                ]
            ])->redirect('/login');
        }

        // Remember the user as authorized.
        Auth::insert(1);
        
        // Redirect to admin page
        (new Response)->redirect('/admin');
    }

    /**
     * Logout
     */
    public static function logout () {
        Auth::logout();

        (new Response)->redirect('/');
    }

    /**
     * Checking Auth.
     * 
     * Can be modified to load the data from DB.
     * For now it is static and for one admin only.
     * 
     * @param string $email Passed email.
     * @param string $password Passed password.
     * 
     * @return bool True - if authentification was been succesfuly. False - if not.
     */
    private static function check_auth (string $email, string $password): bool {
        $email = trim($email);

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        // Check
        if ($email === 'admin@films.ua' && $password === 'admin_pass') {
            return true;
        }

        return false;
    }
}