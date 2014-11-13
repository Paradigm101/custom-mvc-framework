<?php

// A nice class that provide an access to table type classes
// TBD manage foreign key, ...
abstract class Table_Manager_LIB {

    // Table list
    static private $tables = array( 'users', 'sessions', 'roles' );

    static private function executeCommandForAllTables( $command, $stopOnFail = false ) {
        
        // Prepare Answer : array( array( 'name' => tableName, 'result' => BTM_OK/BTM_KO ),
        //                         ... );
        $results = array();

        // For each table
        foreach( static::$tables as $table ) {

            // Get table class
            $className = ucfirst( $table ) . '_TAB';
            $tableClass = new $className();

            // Create table and get result
            $result = $tableClass->$command();

            // Store data in answer array
            $results[] = array( 'tableName' => $table, 'result' => $result );

            // Manage stop on fail option
            if ( $stopOnFail
              && $result == BTM_KO ) {

                return $results;
            }
        }

        // Return results
        return $results;
    }
    
    // Create all DB tables
    // TBD: manage foreign key, create after
    static public function createAllTables( $stopOnFail = false ) {

        return self::executeCommandForAllTables('createTable', $stopOnFail);
    }

    // Delete all DB tables
    // TBD: manage foreign key, delete before
    static public function deleteAllTables( $stopOnFail = false ) {

        return self::executeCommandForAllTables('deleteTable', $stopOnFail);
    }
}
