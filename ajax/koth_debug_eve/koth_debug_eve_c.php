<?php

abstract class Koth_Debug_Eve_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        $num = ( 0 + Url_LIB::getRequestParam('occurence') ) ? : 1;

        for( $i = 0; $i < $num ; $i++ )
        {
            $idMonster1 = Url_LIB::getRequestParam('id_monster1') ? : 19;
            $idMonster2 = Url_LIB::getRequestParam('id_monster2') ? : 23;

            Koth_LIB_Game::startGame( 0 /* idUser1 */, $idMonster1, 0 /* idUser2 */, $idMonster2 );
        }
    }
}
