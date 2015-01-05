<?php

abstract class Koth_Start_Eve_AJA_C extends Base_AJA_C
{
    // Start a new game EvE
    static protected function process()
    {
        $num = ( 0 + Url_LIB::getRequestParam('occurence') ) ? : 1;

        for( $i = 0; $i < $num ; $i++ )
        {
            Koth_LIB_Game::startGame( array( 'id'     => Url_LIB::getRequestParam('id_monster1') ? : 19,
                                             'level'  => 0,
                                             'idUser' => 0 ),
                                      array( 'id'     => Url_LIB::getRequestParam('id_monster2') ? : 23,
                                             'level'  => 0,
                                             'idUser' => 0 ) );
        }
    }
}
