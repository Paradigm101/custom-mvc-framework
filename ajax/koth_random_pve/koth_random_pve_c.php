<?php

abstract class Koth_Random_Pve_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        Koth_LIB_Game::playRandomPvE( Session_LIB::getUserId() );
    }
}
