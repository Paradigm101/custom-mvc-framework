<?php

class Koth_Roll_AJA_M extends Base_AJA_M
{
    // Retrieve number of roll done for this user in the current game
    public function getRollDone( $idUser )
    {
        $idUser = $this->getQuotedValue($idUser);

        $this->query( "SELECT roll_done FROM koth_players WHERE id_user = $idUser ;" );
        $result = $this->fetchNext();

        return $result->roll_done;
    }

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
        
        return $result->to_roll_number;
    }

    // update dice from game_dice: keep = 1 and change id_dice (how? get ids first?)
    // Incremente roll_done for player
    // TBD: should be done in the same transaction
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

        // Anyway, add a roll
        $this->query("UPDATE koth_players SET roll_done = roll_done + 1 WHERE id_user = $idUser ;");
    }
}
