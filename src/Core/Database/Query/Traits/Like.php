<?php 

namespace Divergent\Films\Core\Database\Query\Traits;

use Divergent\Films\Core\Database\Query\Traits\Where;

use Divergent\Films\Core\Database\Query\Interfaces\Query;

/**
 * Provide behavior for enable a LIKE condition to a query.
 */
trait Like {
    // Require using the Where trait.
    use Where;

    /**
     * Add a LIKE condition to a query.
     * 
     * @param string $key Key of a table.
     * @param string $patter Pattern that must be matched.
     * 
     * @return Query A query builder object.
     */
    public function like (string $key, string $pattern): Query {
        $this->where[] = [
            'key' => $key,
            'value' => $pattern,
            'compare' => 'LIKE'
        ];

        return $this;
    }
}