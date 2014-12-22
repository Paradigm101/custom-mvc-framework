<?php

class Koth_Start_AJA_M extends Base_AJA_M
{
    // TBD Manage multiple login => already active game for user
    public function startGame( $userId )
    {
        $userId = $this->getQuotedValue( 0 + $userId );

        // First player: user
        $this->query("INSERT INTO koth_players (id_user, id_hero, current_VP, current_HP, is_active, is_koth, roll_done, rank) VALUES ($userId, 1, 0, 12, 1, 0, 0, 1);");
        $playerId1 = $this->getQuotedValue( 0 + $this->getInsertId() );

        // Second player: computer
        $this->query("INSERT INTO koth_players (id_user, id_hero, current_VP, current_HP, is_active, is_koth, roll_done, rank) VALUES (0, 1, 0, 12, 0, 0, 0, 2);");
        $playerId2 = $this->getQuotedValue( 0 + $this->getInsertId() );

        // Create game
        $this->query("INSERT INTO koth_games (player_number, is_active) VALUES (2, 1);");
        $gameId = $this->getQuotedValue( 0 + $this->getInsertId() );

        // Associate player and game
        $this->query("INSERT INTO koth_game_players (id_game, id_player) VALUES ($gameId, $playerId1), ($gameId, $playerId2);");
    }
}
