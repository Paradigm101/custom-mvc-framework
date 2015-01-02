<?php

abstract class Koth_Close_Game_AJA_C extends Base_AJA_C
{
    // TBD: manage PvP
    static protected function process() 
    {
        // Security check
        if ( Koth_LIB_Game::getStep() == KOTH_STEP_GAME_FINISHED )
        {
            Koth_LIB_Game::closeGame();
        }
    }
}
