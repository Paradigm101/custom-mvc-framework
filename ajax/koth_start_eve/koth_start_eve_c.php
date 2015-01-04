<?php

abstract class Koth_Start_Eve_AJA_C extends Base_AJA_C
{
    // Start a new game EvE
    static protected function process()
    {
        $num = ( 0 + Url_LIB::getRequestParam('occurence') ) ? : 1;

        for( $i = 0; $i < $num ; $i++ )
        {
            Koth_LIB_Game::startGame( array( 'name'   => Url_LIB::getRequestParam('monster1') ? : '5_3_3_3_2',
                                             'level'  => 0,
                                             'idUser' => 0 ),
                                      array( 'name'   => Url_LIB::getRequestParam('monster2') ? : '3_3_3_5_2',
                                             'level'  => 0,
                                             'idUser' => 0 ) );
        }
    }
}
