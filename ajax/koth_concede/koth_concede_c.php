<?php

abstract class Koth_Concede_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        Koth_LIB_Game::setGame( Session_LIB::getUserId(), Url_LIB::getRequestParam('isPvP') ? true : false );
        
        Koth_LIB_Game::userConcedeGame();
    }
}
