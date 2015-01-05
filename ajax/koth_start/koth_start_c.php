<?php

abstract class Koth_Start_AJA_C extends Base_AJA_C
{
    // Start a new game
    static protected function process()
    {
        if ( Koth_LIB_Game::getStatus() == KOTH_STATUS_NOT_STARTED )
        {
            Koth_LIB_Game::startGame( array( 'id'     => Url_LIB::getRequestParam('id_hero') ? : 3,
                                             'level'  => Url_LIB::getRequestParam('level') ? : 3,
                                             'idUser' => Session_LIB::getUserId() ),
                                      array( 'id'     => Url_LIB::getRequestParam('id_monster') ? : 19,
                                             'idUser' => 0 ) );
        }
    }
}
