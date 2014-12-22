<?php

// A nice class that provide an access to table type classes
// TBD manage foreign key, ...
// TBD: new design?
abstract class Table_LIB {

    // Model
    static private $model = null;
    
    // Table list
    static private $tables = array( 'male_first_names',
                                    'female_first_names',
                                    'surnames',
                                    'roles',
                                    'users',
                                    'sessions',
                                    'koth_heroes',
                                    'koth_players',
                                    'koth_games',
                                    'koth_game_players',
                                    'koth_slots',
                                    'koth_game_slots' );

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
    // TBD: manage if user is logged in
    static public function deleteAllTables( $stopOnFail = false ) {

        // Delete recorded tables
        $results = self::executeCommandForAllTables('deleteTable', $stopOnFail);

        // Get model if not done
        if ( !static::$model ) {
            static::$model = new Table_LIB_Model();
        }

        // Delete temporary tables
        static::$model->deleteTemporaryTables();
        
        return $results;
    }
}
