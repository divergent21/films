<?php 

namespace Divergent\Films\Controllers;

use Divergent\Films\Core\Render\Render;
use Divergent\Films\Models\Actor;

final class ActorsController {
    
    public static function index () {
        $actors = Actor::paginate(9)
            ->order_by('last_name')
            ->get();

        $title = 'Actors';

        return Render::view('actors/index', compact('actors', 'title'));
    }

    public static function single (int $id) {
        $actor = Actor::find($id);
        $title = $actor->title();

        return Render::view('actors/single', compact('actor', 'title'));
    }
}