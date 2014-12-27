<?php

// TBD: first turn with only 3 dice
class Koth_LIB_Game_Model extends Base_LIB_Model
{
    private $idUser;

    public function __construct( $idUser )
    {
        parent::__construct();
        
        $this->idUser = $this->getQuotedValue( 0 + $idUser );
    }

    public function isGameActive()
    {
        $this->query("SELECT COUNT(1) is_active FROM koth_games g INNER JOIN koth_players p ON p.id_game = g.id AND p.id_user = {$this->idUser} WHERE g.is_completed = 0;");

        return ( $this->fetchNext()->is_active ? true : false );
    }

    public function startGame()
    {
        // If a game is already active, do nothing
        // Front will refresh page and user will see current game
        // User can concede to start a new game
        if ( $this->isGameActive() )
        {
            return;
        }

        // First, create game
        $query = <<<EOD
INSERT INTO
    koth_games (id_active_player, id_step, is_completed, starting_date )
SELECT
    0,
    s.id,
    0,
    CURRENT_TIMESTAMP
FROM
    koth_steps s
WHERE
    s.name = 'start_turn'
EOD;
        $this->query($query);
        $idGame = $this->getQuotedValue( 0 + $this->getInsertId() );

        // Decide randomly who is starting
        if (rand(0, 1) % 2)
        {
            $playerRank      = $this->getQuotedValue(1);
            $otherPlayerRank = $this->getQuotedValue(2);
        }
        else {
            $playerRank      = $this->getQuotedValue(2);
            $otherPlayerRank = $this->getQuotedValue(1);
        }

        // TBD: manage when AI starts
        $playerRank      = $this->getQuotedValue(1);
        $otherPlayerRank = $this->getQuotedValue(2);

        // Then create players
        $query = <<<EOD
INSERT INTO
    koth_players (id_user, id_game, id_hero, current_vp, current_hp, current_xp, rank )
SELECT
    {$this->idUser},
    $idGame,
    h.id,
    hl.start_vp,
    hl.start_hp,
    hl.start_xp,
    $playerRank
FROM
    koth_heroes h
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = h.id
WHERE
    h.id = 1
UNION
SELECT
    0,
    $idGame,
    h.id,
    hl.start_vp,
    hl.start_hp,
    hl.start_xp,
    $otherPlayerRank
FROM
    koth_heroes h
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = h.id
WHERE
    h.id = 1
EOD;
        $this->query($query);
        
        $query = <<<EOD
UPDATE
    koth_games g
    INNER JOIN koth_players p ON
        p.id_game = g.id
    AND p.rank    = 1
SET
    g.id_active_player = p.id
WHERE
    g.id = $idGame;
EOD;
        $this->query($query);

        // Insert starting dice for active player
        $query = <<<EOD
INSERT INTO
    koth_players_dice (id_player, id_die_type, keep, value)
SELECT
    p.id,
    dt.id,
    0,
    0
FROM
    koth_players p
    INNER JOIN koth_games g ON
        g.id           = p.id_game
    AND g.is_completed = 0
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
        AND p2.id_user = {$this->idUser}
    INNER JOIN koth_die_types dt ON
        dt.name = 'unknown'
WHERE
    p.rank = 1
EOD;
        for ( $i = 0; $i < 4; $i++ )
        {
            $this->query($query);
        }
        
        // Insert starting dice for INactive player
        $query = <<<EOD
INSERT INTO
    koth_players_dice (id_player, id_die_type, keep, value)
SELECT
    p.id,
    dt.id,
    0,
    0
FROM
    koth_players p
    INNER JOIN koth_games g ON
        g.id           = p.id_game
    AND g.is_completed = 0
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
        AND p2.id_user = {$this->idUser}
    INNER JOIN koth_die_types dt ON
        dt.name = 'unknown'
WHERE
    p.rank = 2
EOD;
        for ( $i = 0; $i < 6; $i++ )
        {
            $this->query($query);
        }
    }

    public function concedeGame()
    {
        // Rince players'dice
        $query = <<<EOD
DELETE
    pd
FROM
    koth_players_dice pd
    INNER JOIN koth_players p ON
        p.id = pd.id_player
        INNER JOIN koth_games g ON
            g.id           = p.id_game
        AND g.is_completed = 0
            INNER JOIN koth_players p2 ON
                p2.id_game = g.id
            AND p2.id_user = {$this->idUser}
EOD;
        $this->query($query);

        // TBD: Add xp to users and heroes

        $query = <<<EOD
UPDATE
    koth_games g
    INNER JOIN koth_players p ON
        p.id_game = g.id
    AND p.id_user  = {$this->idUser}
    INNER JOIN koth_players p2 ON
        p2.id_game  = g.id
    AND p2.id_user != {$this->idUser}
SET
    g.is_completed    = 1,
    g.id_winning_user = p2.id_user,
    g.id_losing_user  = {$this->idUser}
WHERE
    g.is_completed = 0;
EOD;
        $this->query($query);
    }
}
