<?php

abstract class Koth_PvE_PAG_C extends Base_PAG_C
{
    static protected function process()
    {
        // Set game to PvE and get scores before running game
        Koth_LIB_Game::setGame( Session_LIB::getUserId(), false /* isPvP */ );
    }
}
