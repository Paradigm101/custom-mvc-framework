<?php

abstract class Koth_Roll_AJA_C extends Base_AJA_C
{
    // TBD: everything should be done in the same transaction
    static protected function process()
    {
        $game = new Koth_LIB_Game( Session_LIB::getUserId() );
        $game->roll();
    }
}
