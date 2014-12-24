<?php

class Koth_Roll_AJA_M extends Base_AJA_M
{
    public function getDiceNumberToReroll( $idUser )
    {
        $idUser = $this->getQuotedValue($idUser);

        $query = <<<EOD
SELECT
    COUNT(1) to_roll_number
FROM
    koth_game_dice gd
    INNER JOIN koth_game_players gp ON
        gp.id_game = gd.id_game
        INNER JOIN koth_players p ON
            p.id      = gp.id_player
        AND p.id_user = $idUser
WHERE
    gd.keep = 0
GROUP BY 
    gd.id_game;
EOD;

        $this->query($query);
        $result = $this->fetchNext();
        
        return ( $result ? $result->to_roll_number : 0 );
    }

    // update dice from game_dice: keep = 1 and change id_dice (how? get ids first?)
    // Incremente roll_done for player
    public function updateDice( $idUser, $newDice )
    {
        $idUser = $this->getQuotedValue( 0 + $idUser );

        // Get ids to dice to reroll
        $query = <<<EOD
SELECT
    gd.id   id_gd
FROM
    koth_game_dice gd
    INNER JOIN koth_game_players gp ON
        gp.id_game = gd.id_game
        INNER JOIN koth_players p ON
            p.id      = gp.id_player
        AND p.id_user = $idUser
WHERE
    gd.keep = 0;
EOD;

        $this->query($query);

        // Update game_dice with new keep and id_dice
        $queries = array();
        foreach ( $newDice as $diceName )
        {
            $result = $this->fetchNext();

            $idGd     = $this->getQuotedValue( 0 + $result->id_gd );
            $diceName = $this->getQuotedValue( $diceName );

            $queries[] = <<<EOD
UPDATE
    koth_game_dice gd
    INNER JOIN koth_dice d ON
        d.name = $diceName
SET
    gd.id_dice = d.id,
    gd.keep    = 1
WHERE
    gd.id = $idGd;
EOD;
        }

        foreach ($queries as $query )
        {
            $this->query($query);
        }
    }
}
