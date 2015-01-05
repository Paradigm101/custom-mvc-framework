<?php

abstract class Koth_Debug_Pvp_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        $idUser1 = Session_LIB::getUserId();
        $idUser2 = Url_LIB::getRequestParam('iduser2') ? : 2;

        if ( ( ENVIRONMENT == 'test' )
           &&( !Koth_LIB_Game::isPlayingPvP( $idUser1 ) )
           &&( !Koth_LIB_Game::isPlayingPvP( $idUser2 ) ) )
        {
            Koth_LIB_Game::removeFromPvPQueue( $idUser1 );
            Koth_LIB_Game::removeFromPvPQueue( $idUser2 );

            $idHero1 = Url_LIB::getRequestParam('id_hero1') ? : 3;
            $level1  = Url_LIB::getRequestParam('level1') ? : 3;
            $idHero2 = Url_LIB::getRequestParam('id_hero2') ? : 1;
            $level2  = Url_LIB::getRequestParam('level2') ? : 3;
            
            Koth_LIB_Game::startGame( $idUser1, $idHero1, $idUser2, $idHero2, $level1, $level2 );
        }
    }
}
