<?php

abstract class Koth_Roll_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        $idUser = Session_LIB::getUserId();

        // Already more than 2 rolls done, can't roll anymore
        if ( static::$model->getRollDone( $idUser ) > 2 )
        {
            return;
        }

        // Algo: get new dice (name) for reroll (given the number of dice to reroll)
        $newDice = Koth_LIB::getRandomDieNames( static::$model->getDiceNumberToReroll( $idUser ) );

        // Update DB with the new roll
        static::$model->updateDice( Session_LIB::getUserId(), $newDice );
    }
}
