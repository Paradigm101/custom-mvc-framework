<?php

abstract class Koth_PvP_PAG_C extends Base_PAG_C
{
    static protected function process()
    {
        // Set game to PvP and get scores before running game
        Koth_LIB_Game::setGame( Session_LIB::getUserId(), true /* isPvP */ );
    }
}
