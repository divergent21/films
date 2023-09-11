<?php 

namespace Divergent\Films\Core\Database\Query;

use Divergent\Films\Core\Database\Query\Interfaces\Query;

/**
 * Builder for query insert.
 */
final class Insert implements Query {

    public function __construct (
        private string $table_name,
        private array $data
    ) {}

    /**
     * Consume the query builder object to SQL query string.
     * 
     * @return string Resulting SQL query.
     */
    public function build (): string {
        $result = 'INSERT INTO ' . $this->table_name;
        
        $result .= ' (' . join(', ', array_map(
            fn (string $key) => '`' . $key . '`',
            array_keys($this->data)
        )) . ') ';

        return $result . 'VALUES (' . join(', ', array_map(
            fn (string $value) => '"' . $value . '"',
            array_values($this->data)
        )) . ');';
    }
}