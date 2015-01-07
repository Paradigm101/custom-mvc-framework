<?php

class Koth_Die_Change_AJA_M extends Base_AJA_M
{
    public function dieChange( $idDie, $keep, $idUser )
    {
        $idDie = $this->getQuotedValue( 0 + $idDie );
        $keep  = $this->getQuotedValue( 0 + $keep );
        $idUser  = $this->getQuotedValue( 0 + $idUser );

        $this->query( "UPDATE "
                        . "koth_players_dice pd "
                        . "INNER JOIN koth_players p ON "
                            . "p.id = pd.id_player "
                        . "AND p.id_user = $idUser "
                    . "SET "
                        . "pd.keep = $keep "
                    . "WHERE "
                        . "pd.id = $idDie ;" );
    }
}
