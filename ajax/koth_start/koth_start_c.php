<?php

abstract class Koth_Start_AJA_C extends Base_AJA_C
{
    // Start a new game
    static protected function process()
    {
        $game = new Koth_LIB_Game( Session_LIB::getUserId() );
        $game->startGame();
        
        Log_LIB::trace(Url_LIB::getRequestParam('hero'), 'hero');
        Log_LIB::trace(Url_LIB::getRequestParam('hero_level'), 'hero_level');
        Log_LIB::trace(Url_LIB::getRequestParam('opponent'), 'opponent');
    }
}
