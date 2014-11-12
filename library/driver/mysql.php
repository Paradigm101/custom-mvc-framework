<?php

/**
 * Description: The MySQL driver provides interaction with a MySQL database
 */
class Driver_LIB_Mysql {

    // Connection holds MySQLi resource
    // TBD: can it be static to share with all implemented drivers?
    private $connection;

    // Result holds data retrieved from server (only an object)
    private $result;

    // Number of rows in current result
    private $rows;

    // Current error
    private $error;

    // Last query
    private $query;

    // Constructor
    public function __construct() {

        $this->connection = null;
        $this->result     = null;
        $this->rows       = 0;
        $this->error      = '';
        $this->query      = '';

        // Connection
        $this->connection = mysqli_connect( DB_ADDRESS, DB_USER, DB_PASSWORD, DB_NAME, NULL /* Port */, NULL /* Socket */ );
    }

    // Close connection when driver is destructed
    public function __destruct() {

        // Free memory from results
        $this->freeResult();

        // Disconnect from database
        mysqli_close( $this->connection );
    }

    // Frees the result set
    private function freeResult() {

        if ( is_object($this->result) ) {
            mysqli_free_result( $this->result );
        }

        $this->result = null;
        $this->rows   = 0;
        $this->error  = '';
    }

    // Specific to each connection type
    public function getQuotedValue( $data ) {

        switch ( strtolower( gettype( $data ) ) ) {

            case 'string':
                return "'" . $this->connection->real_escape_string( $data ) . "'";

            case 'boolean':
                return $data ? 'TRUE' : 'FALSE';

            case 'null':
                return 'NULL';

            case 'integer':
            case 'double':
                return $data;

            case 'array':
            case 'object':
            case 'resource':
                
                Log_LIB::trace( '[Driver_LIB_Mysql] trying to escape wrong data type for query, type [' . gettype( $data ) . "] data [$data]" );
                return null;

            default:
                Log_LIB::trace( '[Driver_LIB_Mysql] trying to escape wrong data type for query, unknow type [' . gettype( $data ) . "] data [$data]" );
                return null;
        }
    }

    // Error management
    public function getLastError() {

        return $this->error;
    }

    // Error management
    public function getLastQuery() {

        return $this->query;
    }

    /**
     * Returns the ID of the row affected by an insert statement.
     * TBD: what if several elements inserted at the same time?
     */
    public function getInsertId() {

        return mysqli_insert_id( $this->connection );
    }

    /**
     * Return the number of rows affected by the last query.
     */
    public function getAffectedRows() {

        return mysqli_affected_rows( $this->connection );
    }

    /**
     * Execute a prepared query
     */
    public function queryDB( $query ) {

        // Free existing results
        $this->freeResult();

        // Store query
        $this->query = $query;

        /** Everything is OK, do the job
         *  Result is equal to:
         *      false on failure
         *      returned object if SELECT, SHOW, DESCRIBE or EXPLAIN
         *      true else
         */
        try {
            $result = mysqli_query( $this->connection, $query );
        }
        // Unknown exception
        catch(Exception $e)
        {
            $this->error = $e->getMessage();
            return false;
        }

        // Problem
        if ( $result === false )
        {
            $this->error = mysqli_error( $this->connection );
            return false;
        }

        // Stock results and row number for SELECT, SHOW, DESCRIBE or EXPLAIN
        if ( is_object($result) )
        {
            $this->result = $result;
            $this->rows   = mysqli_num_rows( $this->result );
        }

        // Return success
        return true;
    }

    /**
     * Fetch a row from the query result
     *
     * @param $type
     */
    public function fetchNext( $type = 'object' ) {

        // Case nothing to fetch
        if ( $this->rows == 0 ) {
            return null;
        }

        // Fetching
        $toReturn = null;
        switch ( $type ) {

            case 'array':
                
                // Retrieve badly formatted data
                $tmpArray = mysqli_fetch_array( $this->result );

                // Convert data for better usage
                $i = 0;
                foreach( $tmpArray as $key => $value )
                    if ( $i++ % 2 )
                        $toReturn[ $key ] = $value;
                break;

            case 'object':
            default:
                $toReturn = mysqli_fetch_object( $this->result );
        }

        // Decrease rows and Free memory if no results left
        if ( --$this->rows == 0 )
            $this->freeResult();

        // Return data
        return $toReturn;
    }

    /**
     * Fetch all rows from the query result
     *
     * @param $type
     */
    public function fetchAll( $type = 'object' ) {

        $toReturn = array();

        while( $this->rows )
            $toReturn[] = $this->fetchNext( $type );

        return $toReturn;
    }
}
