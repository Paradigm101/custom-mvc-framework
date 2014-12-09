<?php

abstract class Cb_Change_AJA_C extends Base_AJA_C {
    
    static protected function process () {
        
        $cbId      = Url_LIB::getRequestParam('cb_id');
        $isChecked = Url_LIB::getRequestParam('is_checked');
        $tableName = Url_LIB::getRequestParam('table_name');

        Log_LIB::trace( "cbId [$cbId] - isChecked [$isChecked] - tableName [$tableName]" );
    }
}
