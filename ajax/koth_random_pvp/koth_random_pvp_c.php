<?php

abstract class Koth_Random_Pvp_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        Koth_LIB_Game::playRandomPvP( Session_LIB::getUserId() );
    }
}
