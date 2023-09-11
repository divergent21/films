<?php 

namespace Divergent\Films\Core\Database\Query;

use Divergent\Films\Core\Database\Query\Traits\Where;
use Divergent\Films\Core\Database\Query\Traits\Like;

use Divergent\Films\Core\Database\Query\Interfaces\Query;

/**
 * Builder for query select.
 */
final class Select implements Query {
    use Where, Like;

    /**
     * @var array $fields Fields which need to select.
     */
    private array $fields;

    /**
     * @var string $from Table name.
     */
    private string $from;

    /**
     * @var string $order_by Key which will use to order.
     */
    private string $order_by;

    /**
     * @var strign $order ASC or DESC
     */
    private string $order;

    /**
     * @var int $limit Limit of rows.
     */
    private int $limit;

    /**
     * @var int $offset Offset for a query.
     */
    private int $offset;

    public function __construct (array $fields = ['*']) {
        $this->fields = $fields;
        $this->order = 'ASC';
    }

    /**
     * Setter for a table name.
     * 
     * @param string $table_name Table name.
     * 
     * @return self A query builder object.
     */
    public function from (string $table_name): self {
        if (! isset($this->from) || empty($this->from)) {
            $this->from = $table_name;
        }

        return $this;
    }

    /**
     * Setter for the order_by.
     * 
     * @param string $key Key for ordering.
     * 
     * @return self A query builder object.
     */
    public function order_by (string $key): self {
        if (! isset($this->order_by) || empty($this->order_by)) {
            $this->order_by = $key;
        }

        return $this;
    }

    /**
     * Setter for the order rule.
     * 
     * @param string $order ASC or DESC
     * 
     * @return self A query builder object.
     */
    public function order (string $order): self {
        $order = strtoupper($order);

        if (in_array($order, ['DESC', 'ASC'])) {
            $this->order = $order;
        }

        return $this;
    }

    /**
     * Setter for the limit rows.
     * 
     * @param int $limit Count of rows will be selectoed.
     * 
     * @return self A query builder object.
     */
    public function limit (int $limit): self {
        if (! isset($this->limit) || $this->limit == 0) {
            $this->limit = $limit;
        }

        return $this;
    }

    /**
     * Setter for the offset.
     * 
     * @param int $offset Count of rows will be ignored from start.
     * 
     * @return self A query builder object.
     */
    public function offset (int $offset): self {
        if (! isset($this->offset) || $this->offset == 0) {
            $this->offset = $offset;
        }

        return $this;
    }

    /**
     * Consume the query builder object to SQL query string.
     * 
     * @return string Resulting SQL query.
     */
    public function build (): string {
        $result = 'SELECT ' . join(', ', $this->fields);

        $result .= ' FROM ' . $this->from;

        $result .= $this->parse_where();

        if (! empty($this->order_by)) {
            $result .= ' ORDER BY ' . $this->order_by . ' ' . $this->order;
        }

        if ($this->limit ?? false) {
            $result .= ' LIMIT ' . $this->limit;
        }

        if ($this->offset ?? false) {
            $result .= ' OFFSET ' . $this->offset;
        }

        return $result . ';';
    }

}