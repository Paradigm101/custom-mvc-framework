<?php

// Api commande that create the database
abstract class Create_Db_Api_Controller extends Base_Api_Controller {

    static protected function process () {

        // Get list of table to create in the good order
        $tables = array( 'users'/*,
                         'session'*/ );

        // For each table
        foreach( $tables as $table ) {

            // Get table class
            $className = ucfirst( $table ) . '_Table_Model';
            
            // Create table
            if ( $className::createTable() == BTM_KO ) {

                // if something wrong happen, stop
                static::setAnswer("Problem with table : $table");
                exit();
            }
        }

        // Return answer
        static::setAnswer('Done');
    }
}
