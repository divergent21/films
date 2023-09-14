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

    public static function save () {
        extract($_POST);

        $actor = Actor::find($id);

        if ($actor === null) {
            (new Response)->redirect('/admin/actors');
        }

        if (! empty($first_name)) {

            // Max length
            if (strlen($first_name) > 255) {
                $first_name = substr($first_name, 0, 255);
            }

            $actor->first_name = $first_name;
        } else {
            (new Response)->with(['error' => 'The first name field is required'])
                ->redirect('/admin/actors/' . $actor->id);
        }

        if (! empty($last_name)) {
            
            // Max length
            if (strlen($last_name) > 255) {
                $last_name = substr($last_name, 0, 255);
            }

            $actor->last_name = $last_name;
        } else {
            $actor->last_name = '';
        }

        $actor->update();

        (new Response)->with(['success' => 'The actor was updated.'])
            ->redirect('/admin/actors');
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

        (new Response)->with(['success' => 'The actors was deleted.'])
            ->redirect('/admin/actors');
    }
}