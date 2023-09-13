<?php 

namespace Divergent\Films\Controllers\Admin;

use Divergent\Films\Models\Actor;
use Divergent\Films\Core\Render\Render;
use Divergent\Films\Core\Response\Response;
use Divergent\Films\Models\FilmToActor;

final class ActorsController {

    public static function index () {
        $actors = Actor::paginate()
            ->order_by('last_name')
            ->get();
        
        $title = 'Actors';

        return Render::view(
            'admin/actors/index', 
            compact('actors', 'title')
        );
    }

    public static function single (int $id) {
        $actor = Actor::find($id);

        if ($actor === null) {
            return (new Response)->redirect('/admin/actors');
        }

        $title = $actor->title();

        return Render::view(
            'admin/actors/single', 
            compact('actor', 'title')
        );
    }

    public static function update () {
        extract($_POST);

        $actor = Actor::find($id);

        if ($actor === null) {
            return (new Response)->redirect('/admin/actors');
        }

        if (! empty($first_name)) {
            $actor->first_name = $first_name;
        }

        if (! empty($last_name)) {
            $actor->last_name = $last_name;
        }

        $actor->update();

        return (new Response)->redirect('/admin/actors');
    }

    public static function delete (int $id) {
        $actor = Actor::find($id);

        if ($actor === null) {
            return (new Response)->redirect('/admin/actors');
        }

        foreach (FilmToActor::where([
            'actor_id' => $actor->id
        ])->get() as $to_delete) {
            $to_delete->delete();
        }

        $actor->delete();

        return (new Response)->redirect('/admin/actors');
    }
}