<?php

abstract class Koth_Play_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        // arguments
        $isDebug = false;

        $isPvE = false;
        $isPvP = false;
        $isEvE = false;

        $isRandom = false;
        $isHero   = false; // dungeon

        $isAdventure = false; // need hero id

        // Call the good play
//        Koth_LIB_Game::playRandomPvE( Session_LIB::getUserId() );
    }
}
