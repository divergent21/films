<?php 

namespace Divergent\Films\Core\Database\Query\Traits;

use Divergent\Films\Core\Database\Query\Interfaces\Query;

/**
 * Provide behavior for enable a WHERE condition to a query.
 */
trait Where {

    /**
     * @var array $where Array of all where conditions.
     */
    private array $where;

    /**
     * Add a WHERE condition to a query
     * 
     * @param string $key Key of a table.
     * @param string $value Value that need to be equals by the compare.
     * @param string $compare Compare rule.
     * 
     * @return Query A query builder object.
     */
    public function where (
        string $key, 
        string $value, 
        string $compare = '='
    ): Query {
        $this->where[] = compact('key', 'value', 'compare');

        return $this;
    }

    /**
     * Parse a WHERE conditions of the query builder to SQL WHERE.
     * 
     * @return string SQL WHERE.
     */
    private function parse_where (): string {
        if (empty($this->where)) return '';

        return ' WHERE ' . join(' AND ', array_map(
            fn (array $where) => 
                $where['key'] . ' ' . 
                $where['compare'] . ' "' . 
                $where['value'] . '"'
            , 
            $this->where
        ));
    }
}