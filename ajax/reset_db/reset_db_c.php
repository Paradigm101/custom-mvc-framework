<?php

// Ajax commande that create the database
abstract class Reset_Db_AJA_C extends Base_AJA_C {

    static protected function process () {

        // Start answer
        $answer = "Delete database" . ALL_EOL
                . "---------------" . ALL_EOL;

        // Delete all tables
        foreach ( Table_LIB::deleteAllTables( /* Don't stop on fail */ ) as $result ) {

            // Add result for user
            $answer .= ucfirst( $result[ 'tableName' ] ) . ' : ' . ( $result[ 'error' ] ? 'Fail [' . $result[ 'error' ] . '] ***************** ' : 'Success' ) . ALL_EOL;
        }

        // Continue answer
        $answer .= ALL_EOL
                . "Create database" . ALL_EOL
                . "---------------" . ALL_EOL;

        // Create all tables
        foreach ( Table_LIB::createAllTables( /* Don't stop on fail */ ) as $result ) {

            // Add result for user
            $answer .= ucfirst( $result[ 'tableName' ] ) . ' : ' . ( $result[ 'error' ] ? 'Fail [' . $result[ 'error' ] . '] ***************** ' : 'Success' ) . ALL_EOL;
        }

        // Return answer
        static::addAnswer('message', $answer);
    }
}
