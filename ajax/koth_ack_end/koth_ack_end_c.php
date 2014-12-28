<?php

abstract class koth_Ack_End_AJA_C extends Base_AJA_C
{
    // Start a new game
    static protected function process() 
    {
        $game = new Koth_LIB_Game( Session_LIB::getUserId() );

        // Security, Ack end can NOT be done
        //      if user is NOT active
        //   OR if it's NOT after the third roll
        if (  ( !$game->isUserActive() )
            ||( $game->getStep() != KOTH_STEP_AFTER_ROLL_3 ) )
        {
            return;
        }

        $game->processAck();
    }
}
