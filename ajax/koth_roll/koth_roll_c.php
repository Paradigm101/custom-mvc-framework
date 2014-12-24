<?php

abstract class Koth_Roll_AJA_C extends Base_AJA_C
{
    // TBD: everything should be done in the same transaction
    static protected function process()
    {
        $idUser = Session_LIB::getUserId();

        // Check if user can still roll
        if ( !in_array( Koth_LIB::getPlayerStatus($idUser), array( 'before_roll_1', 'after_roll_1', 'after_roll_2' ) ) )
        {
            return;
        }

        // Algo: get new dice (name) for reroll (given the number of dice to reroll)
        $newDice = Koth_LIB::getRandomDieNames( static::$model->getDiceNumberToReroll( $idUser ) );

        // Update player's status
        Koth_LIB::setNextStep($idUser);

        // Update DB with the new roll
        static::$model->updateDice( $idUser, $newDice );
    }
}
