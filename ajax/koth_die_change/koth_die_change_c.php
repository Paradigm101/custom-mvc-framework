<?php

abstract class Koth_Die_Change_AJA_C extends Base_AJA_C
{
    protected static function process()
    {
        // Add user for security purpose
        static::$model->dieChange( Url_LIB::getRequestParam('id'), Url_LIB::getRequestParam('keep' ), Session_LIB::getUserId() );
    }
}
