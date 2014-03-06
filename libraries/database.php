<?php

/**
 * Description: generic abstract class for database libraries
 */
abstract class Database_Library
{
    abstract public function queryDB( $query );
    abstract public function fetchNext( $type );
    abstract public function fetchAll( $type );
}
