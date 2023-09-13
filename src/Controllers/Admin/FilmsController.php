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

        $actors = Actor::all()->get();

        $title = $film->title;

        return Render::view(
            'admin/films/single', 
            compact('film', 'title', 'actors')
        );
    }

    public static function new () {
        $title = 'Create new film';
        $actors = Actor::all()->get();

        return Render::view('admin/films/create', compact(
            'title',
            'actors'
        ));
    }

    public static function create () {
        // Extract Title, Year, Format, list of actors.
        extract($_POST);

        $fields = [];

        $title = trim(strip_tags($title));

        if (! empty($title)) {
            if (! empty(Film::where(['title' => $title])->get())) {
                self::response_with_error('Film with the same title is already exists.', 'create');
            }

            $fields['title'] = $title;
        } else {
            self::response_with_error('The title field is required.', 'create');
        }

        if (is_numeric($year)) {
            $fields['since'] = $year . '-01-01';
        } else {
            self::response_with_error('The year must be number.', 'create');
        }

        $format = trim(strip_tags($format));

        if (! empty($format)) {
            $fields['format'] = $format;
        } else {
            self::response_with_error('The film format is unavailable.', 'create');
        }

        // Create film
        $film_id = Film::create($fields);
        if ($film_id === false) {
            self::response_with_error('Failed create a film. Try later.', 'create');
        }

        // Insert all relative actors to the film
        foreach ($actors as $actor_id) {
            FilmToActor::create([
                'actor_id' => $actor_id,
                'film_id' => $film_id
            ]);
        }

        self::response_with_success('The film was created.');
    }

    public static function save () {
        // Extract ID, Title, Year, Format, list of actors.
        extract($_POST);

        $film = Film::find($id);

        if ($film === null) {
            return (new Response)->redirect('/admin/films');
        }

        $title = trim(strip_tags($title));
        
        if (! empty($title)) {
            if (! empty(Film::where(['title' => $title])->get())) {
                self::response_with_error('Film with the same title is already exists.', $film->id);
            }

            $film->title = $title;
        } else {
            self::response_with_error('The title field is required.', $film->id);
        }

        if (is_numeric($year)) {
            $film->since = $year . '-01-01';
        } else {
            self::response_with_error('The year must be number.', $film->id);
        }

        $format = trim(strip_tags($format));

        if (! empty($format)) {
            $film->format = $format;
        } else {
            self::response_with_error('The film format is unavailable.', $film->id);
        }
        
        $film->update();

        // Delete all relative actors to the film
        foreach (FilmToActor::where([
            'film_id' => $film->id
        ])->get() as $to_delete) {
            $to_delete->delete();
        }

        // Insert all relative actors to the film
        foreach ($actors as $actor_id) {
            FilmToActor::create([
                'actor_id' => $actor_id,
                'film_id' => $film->id
            ]);
        }

        self::response_with_success('The films was updated.');
    }

    public static function delete (int $id) {
        $film = Film::find($id);

        // Delete all relative actors to the film
        foreach (FilmToActor::where([
            'film_id' => $film->id
        ])->get() as $to_delete) {
            $to_delete->delete();
        }

        $film->delete();

        self::response_with_success('The films was deleted.');
    }

    private static function response_with_error (string $error, string $page = '') {
        (new Response)->with(['error' => $error])
                ->redirect('/admin/films/' . $page);
    }

    private static function response_with_success (string $success) {
        (new Response)->with(['success' => 'The films was updated.'])
            ->redirect('/admin/films');
    }
}