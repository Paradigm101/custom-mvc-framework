<?php

abstract class Koth_PAG_C extends Base_PAG_C
{
    protected static function process()
    {
        $idUser = Session_LIB::getUserId();

        // Players
        static::$view->assign('players', static::$model->getPlayers( $idUser ) );

        // Board
        static::$view->assign('board', new Koth_LIB_Board( $idUser ) );

        // New
        static::$view->assign('news', new Koth_LIB_News( $idUser ) );
    }
}
