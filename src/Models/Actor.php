<?php 

namespace Divergent\Films\Models;

use Divergent\Films\Core\Database\Model;
use Divergent\Films\Core\Database\Query;
use Divergent\Films\Models\Film;

final class Actor extends Model {

    public function films (): array {
        return array_map(
            fn (array $row) => Film::find($row['film_id']),
            Model::query(
                Query::select(['film_id'])
                    ->from('Films_Actors_relative')
                    ->where('actor_id', $this->id)
                    ->build()
            )
        );
    }

    public function title (): string {
        return join(' ', [$this->last_name, $this->first_name]);
    }
}