<?php

abstract class koth_Ack_End_AJA_C extends Base_AJA_C
{
    // Start a new game
    static protected function process() 
    {
        Koth_LIB_Game::setGame( Session_LIB::getUserId(), Url_LIB::getRequestParam('isPvP') ? true : false );

        // Security check
        if (  ( Koth_LIB_Game::isUserActive() )
            &&( Koth_LIB_Game::getStep() == KOTH_STEP_END_OF_TURN ) )
        {
            Koth_LIB_Game::processEndTurn();
        }
    }
}
