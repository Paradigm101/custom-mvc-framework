<?php

define('MC_ERROR_STATUS_NO_ERROR', 0);
define('MC_ERROR_STATUS_UNKOWN', 1);
define('MC_ERROR_STATUS_DUPLICATE_ENTRY', 2);

/**
 * Mother class for all models
 */
abstract class Base_Model {

    /**
     * Holds instance of database connection
     */
    protected $db;

    // Error from model (TBD:protected good?)
    protected $error;

    public function __construct() {
        $this->db    = new Mysqldriver_Library();
        $this->error = MC_ERROR_STATUS_NO_ERROR;
    }

    // Error management for controller
    public function getLastError()
    {
        return $this->error;
    }

    // TBD: protected good?
    protected function query( $query )
    {
        // Doing the job
        $success = $this->db->queryDB( $query );

        // Return DB id
        if ( $success )
        {
            $this->error = MC_ERROR_STATUS_NO_ERROR;
            return true;
        }

        // Error management
        //      duplicate entry
        if ( strpos($this->getLastDBError(), 'Duplicate entry') !== false )
            $this->error = MC_ERROR_STATUS_DUPLICATE_ENTRY;
        else
            $this->error = MC_ERROR_STATUS_UNKOWN;

        // Problem
        return false;
    }

    // DB data access for controller
    public function getLastDBError()
    {
        return $this->db->getLastError();
    }

    // DB data access for controller
    public function getLastQuery()
    {
        return $this->db->getLastQuery();
    }
}
