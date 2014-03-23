<?php

/**
 * Description: generic abstract class for database libraries
 */
abstract class Database_Library_Controller {

    abstract public function getLastError();
    abstract public function getLastQuery();
    abstract public function getInsertId();
    abstract public function getAffectedRows();
    abstract public function queryDB( $query );
    abstract public function fetchNext( $type );
    abstract public function fetchAll( $type );
    abstract public function getQuotedValue( $data );
}
