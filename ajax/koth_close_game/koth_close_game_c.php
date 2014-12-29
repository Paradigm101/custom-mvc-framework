<?php

abstract class Koth_Close_Game_AJA_C extends Base_AJA_C
{
    // TBD: manage PvP
    static protected function process() 
    {
        $game = new Koth_LIB_Game( Session_LIB::getUserId() );

        // Security
        if (  $game->getStep() != KOTH_STEP_GAME_FINISHED )
        {
            return;
        }

        $game->closeGame();
    }
}
