<?php

abstract class Koth_Queue_Pvp_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        Koth_LIB_Game::playPvP( Session_LIB::getUserId(), Url_LIB::getRequestParam('id_hero') );
    }
}
