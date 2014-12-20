<?php

abstract class Users_Delete_All_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        static::$model->deleteAllUsers( Url_LIB::getRequestParam( 'table_name' ) );
    }
}
