<?php

abstract class Koth_Start_Ai_AJA_C extends Base_AJA_C
{
    // Start a new game AI vs AI (EvE)
    static protected function process()
    {
        Koth_LIB_Game::startGameEvE( Url_LIB::getRequestParam('hero') ? : 'cleric',
                                     Url_LIB::getRequestParam('level') ? : 3,
                                     Url_LIB::getRequestParam('opponent') ? : '5_3_3_3_1' );
    }
}
