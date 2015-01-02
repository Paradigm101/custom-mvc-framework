<?php

abstract class koth_Ack_End_AJA_C extends Base_AJA_C
{
    // Start a new game
    static protected function process() 
    {
        // Security check
        if (  ( Koth_LIB_Game::isUserActive() )
            &&( Koth_LIB_Game::getStep() == KOTH_STEP_AFTER_ROLL_3 ) )
        {
            Koth_LIB_Game::processEndTurn();
        }
    }
}
