<?php

define('BASE_ERROR_STATUS_NO_ERROR',        0);
define('BASE_ERROR_STATUS_UNKOWN',          1);
define('BASE_ERROR_STATUS_DUPLICATE_ENTRY', 2);
define('BASE_ERROR_STATUS_NULL_VALUE',      3);
define('BASE_ERROR_STATUS_NO_TABLE',        4);
define('BASE_ERROR_STATUS_TABLE_EXISTS',    5);
define('BASE_ERROR_STATUS_UNKNOWN_TABLE',   6);

/**
 * Mother class for models from all controllers
 */
abstract class Model_Base_LIB {

    // Instance of database connection
    private $db;

    // Error for controller (interpreted from DB)
    private $error;

    public function __construct() {

        // Dynamic driver (we never know ...)
        $className = 'Driver_LIB_' . ucfirst(DB_TYPE);

        $this->db    = new $className();
        $this->error = BASE_ERROR_STATUS_NO_ERROR;
    }

    // Error management for controller
    public function getLastError() {
        return $this->error;
    }

    // Error management for controller
    public function getLastErrorForUser() {

        switch ($this->error) {
            case BASE_ERROR_STATUS_NO_ERROR:
                return 'No error';
            case BASE_ERROR_STATUS_UNKOWN:
                return 'Unknown error';
            case BASE_ERROR_STATUS_DUPLICATE_ENTRY:
                return 'Duplicate entry';
            case BASE_ERROR_STATUS_NULL_VALUE:
                return "Value cannot null value";
            case BASE_ERROR_STATUS_NO_TABLE:
                return "Table doesn't exists";
            case BASE_ERROR_STATUS_TABLE_EXISTS:
                return 'Table already exists';
            case BASE_ERROR_STATUS_UNKNOWN_TABLE:
                return 'Unknown table';
            default:
                return null;
        }
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
        else if ( ( strpos($this->getLastDBError(), 'Table') !== false ) &&
                  ( strpos($this->getLastDBError(), 'already exists') !== false ) ) {

            $this->error = BASE_ERROR_STATUS_TABLE_EXISTS;
        }
        else if ( strpos($this->getLastDBError(), 'Unknown table') !== false ) {

            $this->error = BASE_ERROR_STATUS_UNKNOWN_TABLE;
        }
        else {
            $this->error = BASE_ERROR_STATUS_UNKOWN;
        }

        // Problem
        return false;
    }

    // DB Wrappers
    //------------
    protected function getQuotedValue( $data ) {
        
        return $this->db->getQuotedValue( $data );
    }
    protected function getInsertId() {
        
        return $this->db->getInsertId();
    }
    protected function getAffectedRows() {
        
        return $this->db->getAffectedRows();
    }
    protected function fetchNext($type = 'object') {
        
        return $this->db->fetchNext($type = 'object');
    }
    protected function fetchAll($type = 'object') {
        
        return $this->db->fetchAll($type = 'object');
    }
}
