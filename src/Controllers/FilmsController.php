<?php 

namespace Divergent\Films\Controllers;

use Divergent\Films\Core\Render\Render;
use Divergent\Films\Models\Film;

final class FilmsController {

    public static function index () {
        $films = Film::paginate(9)
            ->order_by('title')
            ->get();

        return Render::view('films/index', compact('films'));
    }

    public static function single (int $id) {
        $film = Film::find($id);
        $title = $film->title;

        return Render::view('films/single', compact('film', 'title'));
    }
}