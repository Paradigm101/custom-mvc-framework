<?php

// A nice class that provide an access to table type classes
// TBD manage foreign key, ...
abstract class Table_LIB {

    // Table list
    static private $tables = array( 'users', 'sessions', 'roles', 'male_first_names' );

    static private function executeCommandForAllTables( $command, $stopOnFail = false ) {

        // Prepare Answer : array( array( 'name' => tableName, 'error' => '...' ),
        //                         ... );
        $results = array();

        // For each table
        foreach( static::$tables as $table ) {

            // Name is slightly complexe: Xxx_Yyy_Zzz
            $table = implode( '_', array_map( 'ucfirst', explode('_', $table) ) );
            
            // Get table class (name is slightly complexe: Xxx_Yyy_Zzz)
            $className = 'Table_LIB_' . $table;
            $tableClass = new $className();

            // Create table and get result
            $error = $tableClass->$command();

            // Store data in answer array
            $results[] = array( 'tableName' => $table, 'error' => $error );

            // Stop on fail if needed
            if ( $stopOnFail && $error ) {

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
