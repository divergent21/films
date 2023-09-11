<?php 

namespace Divergent\Films\Core\Database\Query;

use Divergent\Films\Core\Database\Query\Traits\Where;
use Divergent\Films\Core\Database\Query\Traits\Like;

use Divergent\Films\Core\Database\Query\Interfaces\Query;

/**
 * Builder for query delete.
 */
final class Delete implements Query {
    use Where, Like;

    public function __construct (
        private string $table_name
    ) {}

    /**
     * Consume the query builder object to SQL query string.
     * 
     * @return string Resulting SQL query.
     */
    public function build (): string {
        return 'DELETE FROM ' . $this->table_name . ' ' . 
            $this->parse_where() . ';';
    }
}