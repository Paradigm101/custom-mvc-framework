<?php

abstract class Users_Delete_Batch_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        static::$model->deleteSelectedUsers( Url_LIB::getRequestParam( 'table_name' ) );
    }
}
