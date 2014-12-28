<?php

class Koth_Die_Change_AJA_M extends Base_AJA_M
{
    public function dieChange( $idDie, $keep )
    {
        $idDie = $this->getQuotedValue( 0 + $idDie );
        $keep  = $this->getQuotedValue( 0 + $keep );

        $this->query( "UPDATE koth_players_dice SET keep = $keep WHERE id = $idDie ;" );
    }
}
