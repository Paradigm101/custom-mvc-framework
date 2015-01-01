<?php

abstract class Koth_PAG_C extends Base_PAG_C
{
    protected static function process()
    {
        if ( Session_LIB::getUserId() )
        {
            // Dashboard
            static::$view->assign('userData',      static::$model->getUserData( Session_LIB::getUserId() ) );
            static::$view->assign('heroesData',    static::$model->getHeroesData( Session_LIB::getUserId() ) );

            // General
            static::$view->assign('game', new Koth_LIB_Game( Session_LIB::getUserId() ) );
        }
    }
}
