<?php

// Api commande that create the database
abstract class Create_Db_Api_Controller extends Base_Api_Controller {
    
    static protected function process () {

        static::setAnswer('Done');
    }
}
