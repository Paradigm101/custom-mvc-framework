<?php

// Api commande that create the database
abstract class Delete_Db_Api_Controller extends Base_Api_Controller {

    static protected function process () {

        // Get list of table to create in the good order
        $tables = array( 'sessions',
                         'users' );

        $answer = "Delete database" . ALL_EOL
                . "---------------" . ALL_EOL;

        // For each table
        foreach( $tables as $table ) {

            // Get table class
            $className = ucfirst( $table ) . '_Table_Controller';
            $tableCreator = new $className();

            // Create table
            if ( $tableCreator->deleteTable() == BTM_KO ) {

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
