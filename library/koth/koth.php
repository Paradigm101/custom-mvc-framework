<?php

abstract class Koth_LIB
{
    static private $model = null;
    
    static private function getModel()
    {
        if ( !static::$model )
        {
            static::$model = new Koth_LIB_Model();
        }

        return static::$model;
    }
    
    // TBD: extend according to specific hero + level?
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

    static public function getPlayerResults( $idUser )
    {
        return static::getModel()->getPlayerResults( $idUser );
    }
    
    static public function concedeGame( $idUser )
    {
        return static::getModel()->concedeGame($idUser);
    }
    
    static public function getPlayerStatus( $idUser )
    {
        return static::getModel()->getPlayerStatus( $idUser );
    }

    static public function getPlayerDice( $idUser )
    {
        return static::getModel()->getPlayerDice( $idUser );
    }
    
    static public function setNextStep( $idUser )
    {
        return static::getModel()->setNextStep( $idUser );
    }
        
    static public function playAI( $idUser )
    {
        return static::getModel()->playAI( $idUser );
    }
}
