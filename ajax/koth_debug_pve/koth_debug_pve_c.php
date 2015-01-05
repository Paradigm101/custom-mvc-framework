<?php

abstract class Koth_Debug_Pve_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        // TBD: check no PvE started

        $idHero1    = Url_LIB::getRequestParam('id_hero') ? : 3;
        $level1     = Url_LIB::getRequestParam('level') ? : 3;
        $idMonster2 = Url_LIB::getRequestParam('id_monster') ? : 19;

        Koth_LIB_Game::startGame( Session_LIB::getUserId(), $idHero1, 0 /* idUser2 */, $idMonster2, $level1 );
    }
}
