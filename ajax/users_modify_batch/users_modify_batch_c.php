<?php

abstract class Users_Modify_Batch_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        static::$model->modifySelectedUsers( Url_LIB::getRequestParam( 'role' ), Url_LIB::getRequestParam( 'table_name' ) );
    }
}
