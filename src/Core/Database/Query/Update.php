<?php 

namespace Divergent\Films\Core\Database\Query;

use Divergent\Films\Core\Database\Query\Traits\Where;

use Divergent\Films\Core\Database\Query\Interfaces\Query;

/**
 * Builder for query update.
 */
final class Update implements Query {
    use Where;

    /**
     * @var string $table_name Table name.
     */
    private string $table_name;

    /**
     * @var array $data Key => value data to update.
     */
    private array $data;

    public function __construct (
        string $table_name,
        array $data
    ) {
        $this->table_name = $table_name;
        $this->data = $data;
    }

    /**
     * Consume the query builder object to SQL query string.
     * 
     * @return string Resulting SQL query.
     */
    public function build (): string {
        return 'UPDATE ' . $this->table_name . ' SET ' . 
            join(', ', array_map(
                fn (string $key) => $key . ' = "' . $this->data[$key] . '"',
                array_keys($this->data)
            )) .
            ' ' . $this->parse_where() . ';'
        ;
    }
}