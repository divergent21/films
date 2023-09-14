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
            (new Response)->redirect('/admin/import_films');
        }

        $data = [];

        // check if the file is .txt
        if ($_FILES['import_file']['type'] == 'text/plain') {
            $data = self::parse_import_file($_FILES['import_file']['tmp_name']);
        } else {
            echo '<pre>' . print_r($_FILES['import_file'], true) . '</pre>';
        }

        if (
            empty($data)
            || ! self::validate_parsed_file($data)
        ) {
            (new Response)->with(['error' => 'File cannot be empty.'])
                ->redirect('/admin/import_films');
        }

        foreach ($data as $film_data) {
            $film_format_fixed = match (strtoupper($film_data['Format'])) {
                'BLU-RAY' => 'Blu-ray', // to one format
                'VHS' => 'VHS',
                'DVD' => 'DVD'
            };

            $prepared_film_params = [
                'title' => strip_tags($film_data['Title']),
                'since' => strip_tags($film_data['Release Year']) . '-01-01',
                'format' => $film_format_fixed
            ];

            // Max length
            if (strlen($prepared_film_params['title']) > 255) {
                $prepared_film_params['title'] = substr($prepared_film_params['title'], 0, 255);
            }

            $already_films = Film::where([
                'title' => $prepared_film_params['title']
            ])->get();

            if (empty($already_films)) {
                $film_id = Film::create($prepared_film_params);
            } else {
                (new Response)->with(['error' => 'Film with "' . $prepared_film_params['title'] . '" title is already exists.'])
                    ->redirect('/admin/import_films');
            }

            foreach ($film_data['Stars'] as $actor_data) {
                $prepared_actor_params = [
                    'first_name' => strip_tags($actor_data[0]),
                    'last_name' => ''
                ];

                if (isset($actor_data[1])) {
                    $prepared_actor_params['last_name'] = strip_tags($actor_data[1]);
                }

                // Max length
                foreach ($prepared_actor_params as $key => $value) {
                    if (strlen($value) > 255) {
                        $prepared_actor_params[$key] = substr($value, 0, 255);
                    }
                }

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

        (new Response)->with(['success' => 'Films were imported.'])
            ->redirect('/admin/films');
    }

    /**
     * Validate the parsed data from the import file.
     * 
     * @param array $parsed The parsed data.
     * 
     * @return bool True - format correct or False.
     */
    private static function validate_parsed_file (array $parsed): bool {
        if (empty($parsed)) return false;

        foreach ($parsed as $film_data) {
            // check for all required fields are exist.
            if (
                ! isset($film_data['Title']) ||
                ! isset($film_data['Release Year']) ||
                ! isset($film_data['Format']) ||
                ! isset($film_data['Stars'])
            ) {
                return false;
            }
        }

        return true;
    }

    private static function parse_import_file (string $path_to_file): array {
        $parsed = [];

        $file_content = file_get_contents($path_to_file);

        foreach (explode("\n\n", $file_content) as $single_film_data) {
            $film_data = [];

            foreach (explode("\n", $single_film_data) as $row) {
                if (empty($row)) continue;

                if (strpos($row, ':') === false) {
                    continue;
                }

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