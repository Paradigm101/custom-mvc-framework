<?php

abstract class koth_Ack_After_Roll_AJA_C extends Base_AJA_C
{
    // Start a new game
    static protected function process() 
    {
        $idUser = Session_LIB::getUserId();

        // Update player's status
        Koth_LIB::setNextStep( $idUser );

        // Compute user's results and see if he won

        // Set user's dice to unknown again
        static::$model->resetDice( $idUser );

        // User didn't won: Have AI play
        Koth_LIB::playAI( $idUser );
    }
}
