<?php

abstract class Koth_Start_AJA_C extends Base_AJA_C
{
    // Start a new game
    static protected function process()
    {
        if ( Koth_LIB_Game::getStatus() == KOTH_STATUS_NOT_STARTED )
        {
            Koth_LIB_Game::startGamePvE( Url_LIB::getRequestParam('hero') ? : 'cleric',
                                         Url_LIB::getRequestParam('level') ? : 3,
                                         Url_LIB::getRequestParam('opponent') ? : '5_3_3_3_1' );
        }
    }
}
