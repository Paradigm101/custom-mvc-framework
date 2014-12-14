<?php

// Ajax commande that create the database
abstract class Create_Db_AJA_C extends Base_AJA_C {

    static protected function process () {

        // Very important!
        parent::process();

        $timeBefore = microtime( true );
        
        // Start answer
        $answer = "Create database" . ALL_EOL
                . "---------------" . ALL_EOL;

        // Ask the table manager to do the work
        foreach ( Table_LIB::createAllTables( /* Don't stop on fail */ ) as $result ) {

            // Add result for user
            $answer .= ucfirst( $result[ 'tableName' ] ) . ' : ' . ( $result[ 'error' ] ? 'Fail [' . $result[ 'error' ] . '] ***************** ' : 'Success' ) . ALL_EOL;
        }

        // Adding time spent
        $duration = microtime( true ) - $timeBefore;
        $answer .= ALL_EOL . 'Total time duration : ' . round( $duration, 3 ) . 's' . ALL_EOL;

        // Store answer
        static::$view->assign('message', $answer);
    }
}
