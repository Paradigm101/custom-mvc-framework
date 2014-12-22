<?php

class Koth_Concede_AJA_M extends Base_AJA_M
{
    public function concedeGame( $idUser )
    {
        $idUser = $this->getQuotedValue($idUser);

        // Get game id
        $query = 'SELECT gp.id_game id_game '
                . 'FROM koth_game_players gp '
                    . 'INNER JOIN koth_players p ON '
                        . '    p.id = gp.id_player '
                        . "AND p.id_user = $idUser ";

        $this->query($query);
        $idGame = $this->fetchNext();
        $idGame = $this->getQuotedValue( 0 + $idGame->id_game );

        // Delete game
        $this->query("UPDATE koth_games SET is_active = 0, id_winning_player = 0 WHERE id = $idGame");

        // Delete player
        $this->query("DELETE p FROM koth_players p INNER JOIN koth_game_players gp ON gp.id_player = p.id AND gp.id_game = $idGame");

        // Delete game-player links
        $this->query("DELETE FROM koth_game_players WHERE id_game = $idGame");

        // Delete game-dice links
        $this->query("DELETE FROM koth_game_dice WHERE id_game = $idGame");
    }
}
