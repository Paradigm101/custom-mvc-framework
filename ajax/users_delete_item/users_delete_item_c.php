<?php

abstract class Users_Delete_Item_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        static::$model->deleteUser( Url_LIB::getRequestParam('id') );
    }
}
