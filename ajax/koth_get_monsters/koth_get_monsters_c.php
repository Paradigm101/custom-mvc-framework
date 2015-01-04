<?php

abstract class Koth_Get_Monsters_AJA_C extends Base_AJA_C
{
    protected static function process()
    {
        static::$view->assign( 'monsters',
                               static::$model->getMonsters( Url_LIB::getRequestParam('level') ? : 1,
                                                            Url_LIB::getRequestParam('ai') ? : 0 ) );
    }
}
