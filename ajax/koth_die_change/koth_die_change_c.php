<?php

abstract class Koth_Die_Change_AJA_C extends Base_AJA_C
{
    protected static function process()
    {
        static::$model->dieChange( Url_LIB::getRequestParam('id'), Url_LIB::getRequestParam('keep' ) );
    }
}
