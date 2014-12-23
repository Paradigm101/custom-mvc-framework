<?php

abstract class Koth_LIB
{
    static public function getRandomDieNames( $num = 1 )
    {
        $dice = array( 'attack', 'heart', 'experience', 'victory_1', 'victory_2', 'victory_3' );
        
        $randomDice = array();
        
        for( $i = 0; $i < $num; $i++ )
        {
            $randomDice[] = $dice[mt_rand(0, count($dice) - 1)];
        }
        
        return $randomDice;
    }
}
