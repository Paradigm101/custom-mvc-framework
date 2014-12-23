<?php

abstract class Koth_LIB
{
    static public function getRandomDieName( $num = 1 )
    {
        return array_rand( array( 'attack', 'heart', 'experience', 'victory_1', 'victory_2', 'victory_3' ), $num );
    }
}
