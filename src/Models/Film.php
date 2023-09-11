<?php 

namespace Divergent\Films\Models;

use Divergent\Films\Core\Database\Model;
use Divergent\Films\Core\Database\Query;
use Divergent\Films\Models\Actor;

final class Film extends Model {

    /**
     * Returning the year from the data.
     * 
     * @return string The year.
     */
    public function year (): string {
        return preg_replace('#^(\d{4})-\d{2}-\d{2}$#si', '$1', $this->since);
    }

    /**
     * Returning the actors list.
     * 
     * @return array The actors list.
     */
    public function actors (): array {
        return array_map(
            fn (array $row) => Actor::find($row['actor_id']),
            Model::query(
                Query::select(['actor_id'])
                    ->from('Films_Actors_relative')
                    ->where('film_id', $this->id)
                    ->build()
            )
        );
    }
}