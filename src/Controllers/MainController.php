<?php

namespace Divergent\Films\Controllers;

use Divergent\Films\Core\Response\Response;

final class MainController {
    public static function code_404 () {
        global $kernel;

        return (new Response)
            ->code(404)
            ->content(
                "<h1>404</h1><pre>" .
                print_r($kernel->router(), true) .
                "</pre>"
            );
    }
}