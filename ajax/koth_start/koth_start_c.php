<?php

abstract class Koth_Start_AJA_C extends Base_AJA_C
{
    // Start a new game
    static protected function process()
    {
        if ( Koth_LIB_Game::getStatus() == KOTH_STATUS_NOT_STARTED )
        {
            Koth_LIB_Game::startGame( Url_LIB::getRequestParam('hero') ? : 'attack_health',
                                      Url_LIB::getRequestParam('hero_level') ? : 1,
                                      Url_LIB::getRequestParam('opponent') ? : '3_3_3_3_1' );
        }
    }
}
