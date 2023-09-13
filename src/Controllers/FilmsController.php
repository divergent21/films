<?php 

namespace Divergent\Films\Controllers;

use Divergent\Films\Core\Render\Render;
use Divergent\Films\Models\Film;

final class FilmsController {

    public static function index () {
        $query = Film::paginate(9)
            ->order_by('title');

        if (isset($_GET['sort']) && in_array($_GET['sort'], ['ASC', 'DESC'])) {
            $query->order($_GET['sort']);
        }

        $films = $query->get();

        return Render::view('films/index', compact('films'));
    }

    public static function single (int $id) {
        $film = Film::find($id);
        $title = $film->title;

        return Render::view('films/single', compact('film', 'title'));
    }
}