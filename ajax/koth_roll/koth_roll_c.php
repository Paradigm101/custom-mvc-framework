<?php

abstract class Koth_Roll_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        $game = new Koth_LIB_Game( Session_LIB::getUserId() );

        // Security, roll can NOT be done
        //      if user is NOT active
        //   OR if it's after the third roll
        if (  ( !$game->isUserActive() )
            ||( $game->getStep() == KOTH_STEP_AFTER_ROLL_3 ) )
        {
            return;
        }
        
        $game->roll();
    }
}
