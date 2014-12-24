<?php

class Koth_Ack_After_Roll_AJA_M extends Base_AJA_M
{
    public function resetDice( $idUser )
    {
        $idUser = $this->getQuotedValue( 0 + $idUser );

        $query = <<<EOD
UPDATE
    koth_game_dice gd
    INNER JOIN koth_game_players gp ON
        gp.id_game = gd.id_game
        INNER JOIN koth_players p ON
            p.id      = gp.id_player
        AND p.id_user = $idUser
SET
    gd.keep    = 0,
    gd.id_dice = 1
;
EOD;

        $this->query($query);
    }
}
