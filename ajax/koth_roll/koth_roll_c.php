<?php

abstract class Koth_Roll_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        // Security
        if ( Koth_LIB_Game::canUserRoll() )
        {
            Koth_LIB_Game::roll();
        }
    }
}
