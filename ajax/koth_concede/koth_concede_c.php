<?php

abstract class Koth_Concede_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        $game = new Koth_LIB_Game( Session_LIB::getUserId() );
        $game->concedeGame();
    }
}
