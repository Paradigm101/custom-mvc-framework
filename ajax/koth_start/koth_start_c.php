<?php

abstract class Koth_Start_AJA_C extends Base_AJA_C
{
    // Start a new game
    static protected function process()
    {
        if ( Koth_LIB_Game::getStatus() == KOTH_STATUS_NOT_STARTED )
        {
            Koth_LIB_Game::startGame( array( 'name'   => Url_LIB::getRequestParam('name') ? : 'cleric',
                                             'level'  => Url_LIB::getRequestParam('level') ? : 3,
                                             'idUser' => Session_LIB::getUserId() ),
                                      array( 'name'   => Url_LIB::getRequestParam('monster') ? : '5_3_3_3_2',
                                             'level'  => 0,
                                             'idUser' => 0 ) );
        }
    }
}
