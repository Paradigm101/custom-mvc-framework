<?php

define('BASE_ERROR_STATUS_NO_ERROR',        0);
define('BASE_ERROR_STATUS_UNKOWN',          1);
define('BASE_ERROR_STATUS_DUPLICATE_ENTRY', 2);
define('BASE_ERROR_STATUS_NULL_VALUE',      3);
define('BASE_ERROR_STATUS_NO_TABLE',        4);

/**
 * Mother class for all models (Page, Ajax, API, ...)
 */
abstract class Base_Library_Model {

    // Holds instance of database connection
    protected $db;

    // Error from model
    protected $error;

    public function __construct() {

        // Dynamic driver (we never know ...)
        $className = ucfirst(DB_TYPE) . 'driver_Library_Controller';

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
        if ( strpos($this->getLastDBError(), 'Duplicate entry') !== false ) {
            
            $this->error = BASE_ERROR_STATUS_DUPLICATE_ENTRY;
        }
        else if ( strpos($this->getLastDBError(), 'cannot be null') !== false ) {
            
            $this->error = BASE_ERROR_STATUS_NULL_VALUE;
        }
        else if ( ( strpos($this->getLastDBError(), 'Table') !== false ) &&
                  ( strpos($this->getLastDBError(), "doesn't exist") !== false ) ) {

            $this->error = BASE_ERROR_STATUS_NO_TABLE;
        }
        else {
            $this->error = BASE_ERROR_STATUS_UNKOWN;
            Log_Library_Controller::trace($this->getLastDBError(), 'BASE_ERROR_STATUS_UNKOWN');
        }

        // Problem
        return false;
    }
}
