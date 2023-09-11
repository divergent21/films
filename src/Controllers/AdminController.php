<?php 

namespace Divergent\Films\Controllers;

use Divergent\Films\Core\Render\Render;
use Divergent\Films\Core\Response\Response;

use Divergent\Films\Models\Actor;
use Divergent\Films\Models\Film;
use Divergent\Films\Models\FilmToActor;

final class AdminController {

    public static function index () {
        return Render::view('admin/index');
    }

    public static function import_films () {
        return Render::view('admin/import_films', [
            'title' => 'Import Films'
        ]);
    }

    public static function import_films_post () {
        if (! isset($_FILES['import_file'])) {
            return (new Response)->redirect('/admin/import_films');
        }

        $data = self::parse_import_file($_FILES['import_file']['tmp_name']);

        foreach ($data as $film_data) {
            $film_format_fixed = match (strtoupper($film_data['Format'])) {
                'BLU-RAY' => 'Blu-ray', // to one format
                'VHS' => 'VHS',
                'DVD' => 'DVD'
            };

            $prepared_film_params = [
                'title' => $film_data['Title'],
                'since' => $film_data['Release Year'] . '-01-01',
                'format' => $film_format_fixed
            ];

            $already_films = Film::where($prepared_film_params)->get();

            if (empty($already_films)) {
                $film_id = Film::create($prepared_film_params);
            } else {
                $film_id = $already_films[0]->id;
            }

            foreach ($film_data['Stars'] as $actor_data) {
                $prepared_actor_params = [
                    'first_name' => $actor_data[0],
                    'last_name' => $actor_data[1]
                ];

                $already_actors = Actor::where($prepared_actor_params)->get();

                if (empty($already_actors)) {
                    $actor_id = Actor::create($prepared_actor_params);
                } else {
                    $actor_id = $already_actors[0]->id;
                }

                // Add relative
                $prepared_relative_params = [
                    'actor_id' => $actor_id,
                    'film_id' => $film_id
                ];

                $alredy_relative = FilmToActor::where($prepared_relative_params)->get();

                if (empty($alredy_relative)) {
                    FilmToActor::create($prepared_relative_params);
                }
            }
        }

        return (new Response)->redirect('/admin/films');
    }

    private static function parse_import_file (string $path_to_file): array {
        $parsed = [];

        $file_content = file_get_contents($path_to_file);

        foreach (explode("\n\n", $file_content) as $single_film_data) {
            $film_data = [];

            foreach (explode("\n", $single_film_data) as $row) {
                if (empty($row)) continue;

                list ($key, $value) = array_map('trim', explode(':', $row));
                $film_data[$key] = $value;
            }

            if (! empty($film_data)) {
                $parsed[] = $film_data;
            }
        }

        foreach ($parsed as &$film_data) {
            $film_data['Stars'] = array_map(function (string $full_name) {
                return explode(' ', $full_name);
            }, array_map('trim', explode(',', $film_data['Stars'])));
        }

        return $parsed;
    }
}