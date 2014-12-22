<?php

abstract class Koth_PAG_C extends Base_PAG_C
{
    protected static function process() {

        static::$view->assign('players', static::$model->getPlayers( Session_LIB::getUserId() ) );
        static::$view->assign('board', static::$model->getBoard( Session_LIB::getUserId() ) );
    }
}
