<?php

// Api commande that create the database
abstract class Create_Db_API_C extends Base_API_C {

    static protected function process () {

        // Get list of table to create in the good order
        // TBD: table manager library to get the list and other stuff (foreign keys, etc...)
        $tables = array( 'users',
                         'sessions' );

        $answer = "Create database" . ALL_EOL
                . "---------------" . ALL_EOL;

        // For each table
        foreach( $tables as $table ) {

            // Get table class
            $className = ucfirst( $table ) . '_TAB';
            $tableCreator = new $className();

            // Create table
            if ( $tableCreator->createTable() == BTM_KO ) {

                // if something wrong happen
                $answer .= "Problem : $table" . ALL_EOL;
            }
            else {
                $answer .= "Success : $table" . ALL_EOL;
            }
        }

        // Return answer
        static::setAnswer($answer);
    }
}
