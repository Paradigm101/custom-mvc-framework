<?php

// A nice class that provide an access to table type classes
// TBD manage foreign key, ...
abstract class Table_LIB {

    // Table list
    static private $tables = array( 'users', 'sessions', 'roles' );

    static private function executeCommandForAllTables( $command, $stopOnFail = false ) {
        
        // Prepare Answer : array( array( 'name' => tableName, 'error' => '...' ),
        //                         ... );
        $results = array();

        // For each table
        foreach( static::$tables as $table ) {

            // Get table class
            $className = 'Table_LIB_' . ucfirst( $table );
            $tableClass = new $className();

            // Create table and get result
            $error = $tableClass->$command();

            // Store data in answer array
            $results[] = array( 'tableName' => $table, 'error' => $error );

            // Manage stop on fail option
            if ( $stopOnFail
              && $error ) {

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
