<?php 

namespace Divergent\Films\Controllers\Admin;

use Divergent\Films\Core\Render\Render;
use Divergent\Films\Core\Response\Response;
use Divergent\Films\Models\Film;
use Divergent\Films\Models\Actor;

use Divergent\Films\Models\FilmToActor;

final class FilmsController {

    public static function index () {
        $films = Film::paginate()
            ->order_by('title')
            ->get();

        $title = 'Films';

        return Render::view(
            'admin/films/index',
             compact('films', 'title')
        );
    }

    public static function single (int $id) {
        $film = Film::find($id);

        if ($film === null) {
            return (new Response)->redirect('/admin/films');
        }

        $actors = Actor::all();

        $title = $film->title;

        return Render::view(
            'admin/films/single', 
            compact('film', 'title', 'actors')
        );
    }

    public static function save () {
        // Extract ID, Title, Year, Format, list of actors.
        extract($_POST);

        $film = Film::find($id);

        if ($film === null) {
            return (new Response)->redirect('/admin/films');
        }
        
        if (! empty($title)) {
            $film->title = $title;
        }

        if (is_numeric($year)) {
            $film->since = $year . '-01-01';
        }

        if (! empty($format)) {
            $film->format = $format;
        }
        
        $film->update();

        // Delete all relative actors to the film
        foreach (FilmToActor::where([
            'film_id' => $film->id
        ]) as $to_delete) {
            $to_delete->delete();
        }

        // Insert all relative actors to the film
        foreach ($actors as $actor_id) {
            FilmToActor::create([
                'actor_id' => $actor_id,
                'film_id' => $film->id
            ]);
        }

        return (new Response)->redirect('/admin/films');
    }

    public static function delete (int $id) {
        $film = Film::find($id);

        // Delete all relative actors to the film
        foreach (FilmToActor::where([
            'film_id' => $film->id
        ]) as $to_delete) {
            $to_delete->delete();
        }

        $film->delete();

        return (new Response)->redirect('/admin/films');
    }
}