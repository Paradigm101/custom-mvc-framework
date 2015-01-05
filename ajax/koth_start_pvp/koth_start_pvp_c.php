<?php

abstract class Koth_Start_Pvp_AJA_C extends Base_AJA_C
{
    // Start a new game
    static protected function process()
    {
        // TBD: manage PvP launch: user 2 should be in a queue or something
        if ( Koth_LIB_Game::getStatus() == KOTH_STATUS_NOT_STARTED )
        {
            Koth_LIB_Game::startGame( array( 'id'     => Url_LIB::getRequestParam('id_hero1') ? : 'cleric',
                                             'level'  => Url_LIB::getRequestParam('level1') ? : 3,
                                             'idUser' => Session_LIB::getUserId() ),
                                      array( 'id'     => Url_LIB::getRequestParam('id_hero2') ? : 'monk',
                                             'level'  => Url_LIB::getRequestParam('level2') ? : 3,
                                             'idUser' => Url_LIB::getRequestParam('iduser2') ? : 2 ) );
        }
    }
}
