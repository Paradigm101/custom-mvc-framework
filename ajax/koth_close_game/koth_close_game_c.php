<?php

abstract class Koth_Close_Game_AJA_C extends Base_AJA_C
{
    // TBD: manage PvP
    static protected function process() 
    {
        Koth_LIB_Game::closeGame( Url_LIB::getRequestParam('id_game') );
    }
}
