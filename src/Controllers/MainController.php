<?php

namespace Divergent\Films\Controllers;

use Divergent\Films\Core\Response\Response;
use Divergent\Films\Core\Render\Render;

use Divergent\Films\Models\Film;
use Divergent\Films\Models\Actor;

final class MainController {

    public static function index () {
        $films = Film::paginate(6)->get();
        $actors = Actor::paginate(6)->get();

        return Render::view('main', compact('films', 'actors'));
    }

    public static function code_404 () {
        return (new Response)
            ->code(404)
            ->content(
                "<h1>404</h1>"
            );
    }
}