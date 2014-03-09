<?php

define('BASE_ERROR_STATUS_NO_ERROR', 0);
define('BASE_ERROR_STATUS_UNKOWN', 1);
define('BASE_ERROR_STATUS_DUPLICATE_ENTRY', 2);
define('BASE_ERROR_STATUS_NULL_VALUE', 3);

/**
 * Mother class for all models
 */
abstract class Base_Model {

    // Holds instance of database connection
    protected $db;

    // Error from model
    protected $error;

    public function __construct() {

        // Dynamic driver (we never know ...
        $className = ucfirst(DB_TYPE) . 'driver_Library';

        $this->db    = new $className();
        $this->error = BASE_ERROR_STATUS_NO_ERROR;
    }

    // Error management for controller
    public function getLastError() {
        return $this->error;
    }

    // DB data access for controller
    public function getLastDBError() {
        return $this->db->getLastError();
    }

    // DB data access for controller
    public function getLastQuery() {
        return $this->db->getLastQuery();
    }

    // The big thing
    protected function query( $query ) {

        // Doing the job
        $success = $this->db->queryDB( $query );

        // Success: retire
        if ( $success ) {
            $this->error = BASE_ERROR_STATUS_NO_ERROR;
            return true;
        }

        // Error management
        //      duplicate entry
        //      unknown error
        if ( strpos($this->getLastDBError(), 'Duplicate entry') !== false ) {
            $this->error = BASE_ERROR_STATUS_DUPLICATE_ENTRY;
        }
        else if ( strpos($this->getLastDBError(), 'cannot be null') !== false ) {
            $this->error = BASE_ERROR_STATUS_NULL_VALUE;
        }
        else {
            $this->error = BASE_ERROR_STATUS_UNKOWN;
            Log_Library::trace($this->getLastDBError(), 'BASE_ERROR_STATUS_UNKOWN');
        }

        // Problem
        return false;
    }
}
