<?php

abstract class Koth_Roll_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        Koth_LIB_Game::setGame( Session_LIB::getUserId(), Url_LIB::getRequestParam('isPvP') ? true : false );

        // Security
        if ( Koth_LIB_Game::canUserRoll() )
        {
            Koth_LIB_Game::roll();
        }
    }
}
