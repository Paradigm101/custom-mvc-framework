<?php

abstract class Koth_PAG_C extends Base_PAG_C
{
    protected static function process()
    {
        static::$view->assign('game', new Koth_LIB_Game( Session_LIB::getUserId() ) );
    }
}
