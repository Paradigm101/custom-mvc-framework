<?php

class Koth_Roll_AJA_M extends Base_AJA_M
{
    public function roll( $idUser )
    {
        $idUser = $this->getQuotedValue($idUser);

        // First: get game id and roll_done
        $query = <<<EOD
SELECT
    p.roll_done     roll_done,
    g.id            id_game
FROM
    koth_players p
    INNER JOIN koth_game_players gp ON
        gp.id_player = p.id
        INNER JOIN koth_games g ON
            g.id        = gp.id_game
        AND g.is_active = 1
WHERE
    p.id_user = $idUser ;
EOD;

        $this->query( $query );

        $result = $this->fetchNext();

        $rollDone = $result->roll_done;
        $idGame   = $this->getQuotedValue( 0 + $result->id_game );

        // More than 3 roll done, nothing to do
        if ( $rollDone >= 2 )
        {
            return;
        }

        // Then get ids from dice to re-roll
        $query = <<<EOD
SELECT
    gd.id   id_dice
FROM
    koth_games g
    INNER JOIN koth_game_dice gd ON
        gd.id_game = g.id
    AND gd.keep    = 0
WHERE
    g.id = $idGame ;
EOD;

        $this->query( $query );

        $ids = array();
        while( $result = $this->fetchNext() )
        {
            $ids[] = $result->id_dice;
        }

        Log_LIB::trace($ids);

        // If there are dice to roll
        if ( count( $ids ) )
        {
            // Find out new dice
            $newDice = Koth_LIB::getRandomDieName( count( $ids ) );

            // Remove entries in koth_game_dice
            $this->query('DELETE FROM koth_game_dice WHERE id IN ( ' . implode(', ', $ids) . ' ) ;');
   
            // Create new entries in koth_game_dice
            $this->query('');
        }

        $newRollDone = $this->getQuotedValue( 1 + $rollDone );

        // Anyway, add a roll
        $this->query("UPDATE koth_players SET roll_done = $newRollDone WHERE id = $idUser ;");
    }
}
