<?php

// Api commande that create the database
abstract class Delete_Db_API_C extends Base_API_C {

    static protected function process () {

        // Start answer
        $answer = "Delete database" . ALL_EOL
                . "---------------" . ALL_EOL;

        // Ask the table manager to do the work
        foreach ( Table_Manager_LIB::deleteAllTables( /* Don't stop on fail */ ) as $result ) {

            // Add result for user
            $answer .= ucfirst( $result[ 'tableName' ] ) . ' : ' . ( $result[ 'error' ] ? 'Fail [' . $result[ 'error' ] . '] ***************** ' : 'Success' ) . ALL_EOL;
        }

        // Return answer
        static::setAnswer($answer);
    }
}
