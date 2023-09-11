<?php 

namespace Divergent\Films\Core\Database\Query\Interfaces;

/**
 * Intarface for all types query requests.
 */
interface Query {
    
    /**
     * Consume a query builder object to SQL query string.
     * 
     * @return string SQL query string.
     */
    public function build (): string;
}