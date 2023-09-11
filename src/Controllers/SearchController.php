<?php 

namespace Divergent\Films\Controllers;

use Divergent\Films\Core\Render\Render;

use Divergent\Films\Models\Film;
use Divergent\Films\Models\Actor;
use Divergent\Films\Models\FilmToActor;

final class SearchController {

    const SEARCH_ALL = 0b111;
    const SEARCH_FILMS = 0b001;
    const SEARCH_ACTORS = 0b010;
    const SEARCH_FILMS_BY_ACTORS = 0b100;

    public static function index () {
        $query = $_GET['s'] ?? '';
        $films = $actors = $films_by_actors = [];

        if (! empty($query) && strlen($query) > 4) {
            $search_type = self::parse_search_type();

            if (self::SEARCH_FILMS & $search_type) {
                $films = self::search_films($query);
            }

            if (self::SEARCH_ACTORS & $search_type) {
                $actors = self::search_actors($query);
            }

            if (self::SEARCH_FILMS_BY_ACTORS & $search_type) {
                $films_by_actors = self::search_films_by_actors(
                    $actors ?: self::search_actors($query)
                );
            }
        }

        $title = join(': ', ['Search', $query]);

        return Render::view(
            'search',
            compact(
                'films', 
                'actors', 
                'films_by_actors',
                'title',
                'query'
            )
        );
    }

    private static function parse_search_type () {
        return match ($_GET['stype'] ?? 'all') {
            'films' => self::SEARCH_FILMS,
            'actors' => self::SEARCH_ACTORS,
            'films_by_actors' => self::SEARCH_FILMS_BY_ACTORS,
            'all' => self::SEARCH_ALL
        };
    }

    private static function search_films (string $query): array {
        return Film::all()
            ->like('title', '%' . $query . '%')
            ->limit(5)
            ->get();
    }

    private static function search_actors (string $query): array {
        $by_last_name = Actor::all()
            ->like('last_name', '%' . $query . '%')
            ->limit(5)
            ->get();

        if (count($by_last_name) < 5) {
            $by_first_name = Actor::all()
                ->like('first_name', '%' . $query . '%')
                ->limit(5)
                ->get();

            return array_slice(array_unique(array_merge(
                $by_last_name, 
                $by_first_name
            ), SORT_REGULAR), 0, 5);
        }

        return $by_last_name;
    }

    private static function search_films_by_actors (array $actors): array {
        $films = [];

        foreach ($actors as $actor) {
            foreach ($actor->films() as $film) {
                $films[] = $film;

                if (count($films) >= 5) {
                    break 2;
                }
            }
        }

        return $films;
    }
}