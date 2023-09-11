<?php

namespace Divergent\Films\Core\Database;

use Divergent\Films\Core\Database\Query\Select;
use Divergent\Films\Core\Database\Query\Update;
use Divergent\Films\Core\Database\Query\Insert;
use Divergent\Films\Core\Database\Query\Delete;

/**
 * Fabric class for query classes.
 */
abstract class Query {

    /**
     * Create Select query class.
     * 
     * @param array $fields = ['*']. Which fields need to request.
     * 
     * @return Select Object of Select query.
     */
    public static function select (array $fields = ['*']): Select {
        return new Select($fields);
    }

    /**
     * Create Update query class.
     * 
     * @param string $table_name. Name of a table for updating.
     * @param array $data. Data key => value for update.
     * 
     * @return Update Object of Update query.
     */
    public static function update (
        string $table_name, 
        array $data
    ): Update {
        return new Update($table_name, $data);
    }

    /**
     * Create Insert query class.
     * 
     * @param string $table_name Name of a table for inserting.
     * @param array $data Data key => value for insert.
     * 
     * @return Insert Object of Insert query.
     */
    public static function insert (
        string $table_name, 
        array $data
    ): Insert {
        return new Insert($table_name, $data);
    }

    /**
     * Create Update query class.
     * 
     * @param string $table_name Name of a table for inserting.
     * 
     * @return Delete Object of Delete query.
     */
    public static function delete (string $table_name): Delete {
        return new Delete($table_name);
    }
}