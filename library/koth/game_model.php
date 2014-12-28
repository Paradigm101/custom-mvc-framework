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

    public function getStep()
    {
        $query = <<<EOD
SELECT
    s.name  step
FROM
    koth_steps s
    INNER JOIN koth_games g ON
        g.id_step      = s.id
    AND g.is_completed = 0
        INNER JOIN koth_players p ON
            p.id_game = g.id
        AND p.id_user = {$this->idUser} 
EOD;
        $this->query($query);
        return $this->fetchNext()->step;
    }
    
    public function isUserActive()
    {
        $query = <<<EOD
SELECT
    COUNT(1)    is_active
FROM
    koth_players p
    INNER JOIN koth_games g ON
        g.id               = p.id_game
    AND g.id_active_player = p.id   
    AND g.is_completed     = 0
WHERE
    p.id_user = {$this->idUser} 
EOD;
        $this->query($query);
        return ( $this->fetchNext()->is_active ? true : false );
    }

    public function stepStart()
    {
        $query = <<<EOD
UPDATE
    koth_games g
    INNER JOIN koth_players p ON
        p.id_game = g.id
    AND p.id_user = {$this->idUser}
    INNER JOIN koth_steps s ON
        s.name = 'start_turn'
SET
    g.id_step = s.id
WHERE
    g.is_completed = 0
EOD;
        $this->query($query);
    }
    
    // Reset active player's dice
    // Set dice number to 6 if needed
    public function resetDice()
    {
        $query = <<<EOD
UPDATE
    koth_players_dice pd
    INNER JOIN koth_players p ON
        p.id = pd.id_player
        INNER JOIN koth_games g ON
            g.id               = p.id_game
        AND g.is_completed     = 0
        AND g.id_active_player = p.id
            INNER JOIN koth_players p2 ON
                p2.id_game = g.id
            AND p2.id_user = {$this->idUser}
    INNER JOIN koth_die_types dt ON
        dt.name = 'unknown'
SET
    pd.id_die_type = dt.id,
    pd.value       = 0,
    pd.keep        = 0
EOD;
        $this->query($query);

        // Exactly 4 dice updated => insert 2 more
        if ( $this->getAffectedRows() == 4 )
        {
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
        g.id               = p.id_game
    AND g.is_completed     = 0
    AND g.id_active_player = p.id
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
        AND p2.id_user = {$this->idUser}
    INNER JOIN koth_die_types dt ON
        dt.name = 'unknown'
EOD;
            $this->query($query);
            $this->query($query);
        }
    }

    // Update turn number if active player at the end of this turn is rank 2
    public function updateTurnNumber()
    {
        $query = <<<EOD
UPDATE
    koth_games g
    INNER JOIN koth_players p ON
        p.id_game = g.id
    AND p.id_user = {$this->idUser}
    INNER JOIN koth_players p2 ON
        p2.id_game = g.id
    AND p2.id      = g.id_active_player
    AND p2.rank    = 2
SET
    g.turn_number = g.turn_number + 1
WHERE
    g.is_completed = 0
EOD;
        $this->query($query);
    }

    public function switchActivePlayer()
    {
        $query = <<<EOD
UPDATE
    koth_games g
    INNER JOIN koth_players p ON
        p.id_game = g.id
    AND p.id_user = {$this->idUser}
    INNER JOIN koth_players p2 ON
        p2.id_game = g.id
    AND p2.id     != g.id_active_player
SET
    g.id_active_player = p2.id
WHERE
    g.is_completed = 0
EOD;
        $this->query($query);
    }

    // Active player's victory over threshold
    // OR non-active player's health is 0 or less
    public function isVictory()
    {
        $query = <<<EOD
SELECT
    COUNT(1)    is_victory
FROM
    koth_players p
    INNER JOIN koth_games g ON
        g.id           = p.id_game
    AND g.is_completed = 0
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
        AND p2.id      = g.id_active_player
        INNER JOIN koth_players p3 ON
            p3.id_game  = g.id
        AND p3.id      != g.id_active_player
WHERE
    p.id_user = {$this->idUser} 
AND (  ( p2.current_vp > 50 ) 
     OR( p3.current_hp < 1 )  )
EOD;
        $this->query($query);

        return ( $this->fetchNext()->is_victory ? true : false );
    }

    // TBD: update game id_winning_user/id_losing_user
    // TBD: update game is_completed
    // TBD: update game xp for winning/losing from game_xp on players and add X%/Y% for winner/loser
    // TBD: update xp on users'heroes and users from game xp
    public function closeGame()
    {
        Log_LIB::trace('close');
    }

    // health add to active player, TBD: not more than starting
    // experience add to active player
    // victory points add to active player
    // attack remove health from non-active player
    // update game_xp for active player
    // TBD: Manage hero level through xp
    public function storeResults( $results, $isActivePlayer = true )
    {
        $activeCondition = ' AND g.id_active_player ' . ( $isActivePlayer ? '' : '!' ) . '= p.id';

        $experience = $this->getQuotedValue( 0 + ( array_key_exists('experience', $results) ? $results['experience'] : 0 ) );
        $victory    = $this->getQuotedValue( 0 + ( array_key_exists('victory', $results) ? $results['victory'] : 0 ) );
        $health     = $this->getQuotedValue( 0 + ( array_key_exists('health', $results) ? $results['health'] : 0 ) );
        $attack     = $this->getQuotedValue( 0 + ( array_key_exists('attack', $results) ? $results['attack'] : 0 ) );
        $gameXp     = $this->getQuotedValue( 0 + $experience + $victory + $health + $attack );

        $query = <<<EOD
UPDATE
    koth_players p
    INNER JOIN koth_games g ON
        g.id           = p.id_game
    AND g.is_completed = 0
    $activeCondition
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
        AND p2.id_user = {$this->idUser}
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = p.id_hero
    AND hl.level   = 1
SET
    p.current_xp = p.current_xp + $experience,
    p.current_vp = p.current_vp + $victory,
    p.current_hp = LEAST( p.current_hp + $health, hl.start_hp ),
    p.game_xp    = p.game_xp + $gameXp
EOD;
        $this->query($query);

        $activeCondition = ' AND g.id_active_player ' . ( $isActivePlayer ? '!' : '' ) . '= p.id';
        
        $query = <<<EOD
UPDATE
    koth_players p
    INNER JOIN koth_games g ON
        g.id           = p.id_game
    AND g.is_completed = 0
    $activeCondition
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
        AND p2.id_user = {$this->idUser}
SET
    p.current_hp = p.current_hp - $attack ;
EOD;
        $this->query($query);
    }

    public function isGameActive()
    {
        $this->query("SELECT COUNT(1) is_active FROM koth_games g INNER JOIN koth_players p ON p.id_game = g.id AND p.id_user = {$this->idUser} WHERE g.is_completed = 0;");

        return ( $this->fetchNext()->is_active ? true : false );
    }

    // TBD: manage different hero/level
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
    koth_players (id_user, id_game, id_hero, hero_level, current_vp, current_hp, current_xp, rank )
SELECT
    {$this->idUser},
    $idGame,
    h.id,
    hl.level,
    hl.start_vp,
    hl.start_hp,
    hl.start_xp,
    $playerRank
FROM
    koth_heroes h
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = h.id
    AND hl.level   = 1
WHERE
    h.id = 1
UNION
SELECT
    0,
    $idGame,
    h.id,
    hl.level,
    hl.start_vp,
    hl.start_hp,
    hl.start_xp,
    $otherPlayerRank
FROM
    koth_heroes h
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = h.id
    AND hl.level   = 1
WHERE
    h.id = 1
EOD;
        $this->query($query);
        
        // Update game with active player
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
        
        // Insert starting dice for non-active player
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

    // Number of dice to reroll for active player
    public function getDiceNumberToRoll()
    {
        $query = <<<EOD
SELECT
    COUNT(1)    dice_number
FROM
    koth_players_dice pd
    INNER JOIN koth_players p ON
        p.id = pd.id_player
        INNER JOIN koth_games g ON
            g.id               = p.id_game
        AND g.is_completed     = 0
        AND g.id_active_player = p.id
            INNER JOIN koth_players p2 ON
                p2.id_game = g.id
            AND p2.id_user = {$this->idUser}
WHERE
    pd.keep = 0
EOD;
        $this->query($query);
        return $this->fetchNext()->dice_number;
    }

    // Get Max Hero values for active player
    public function getHeroMaxValues()
    {
        $query = <<<EOD
SELECT
    hl.max_attack       max_attack,
    hl.max_health       max_health,
    hl.max_experience   max_experience,
    hl.max_victory      max_victory
FROM
    koth_heroes_levels hl
    INNER JOIN koth_players p ON
        p.id_hero    = hl.id_hero
    AND p.hero_level = hl.level
        INNER JOIN koth_games g ON
            g.id               = p.id_game
        AND g.is_completed     = 0
        AND g.id_active_player = p.id
            INNER JOIN koth_players p2 ON
                p2.id_game = g.id
            AND p2.id_user = {$this->idUser}
EOD;
        $this->query($query);
        $max = $this->fetchNext();

        return array( 'attack'     => $max->max_attack,
                      'health'     => $max->max_health,
                      'experience' => $max->max_experience,
                      'victory'    => $max->max_victory );
    }

    public function addStepForRoll()
    {
        $query = <<<EOD
UPDATE
    koth_games g
    INNER JOIN koth_players p ON
        p.id_game = g.id
    AND p.id      = g.id_active_player
    AND p.id_user = {$this->idUser}
SET
    g.id_step = g.id_step + 1
WHERE
    g.is_completed = 0
EOD;
        $this->query($query);
    }

    public function updateDice( $newDice )
    {
        // First select ids
        $query = <<<EOD
SELECT
    pd.id
FROM
    koth_players_dice pd
    INNER JOIN koth_players p ON
        p.id = pd.id_player
        INNER JOIN koth_games g ON
            g.id               = p.id_game
        AND g.id_active_player = p.id
        AND g.is_completed     = 0
            INNER JOIN koth_players p2 ON
                p2.id_game = g.id
            AND p2.id_user = {$this->idUser}
WHERE
    pd.keep = 0
EOD;
        $this->query($query);

        $queries = array();
        foreach ( $newDice as $die )
        {
            $type  = $this->getQuotedValue( $die['type'] );
            $value = $this->getQuotedValue( 0 + $die['value'] );
            $id    = $this->fetchNext()->id;

            $query = <<<EOD
UPDATE
    koth_players_dice pd
    INNER JOIN koth_players p ON
        p.id = pd.id_player
        INNER JOIN koth_games g ON
            g.id               = p.id_game
        AND g.id_active_player = p.id
        AND g.is_completed     = 0
            INNER JOIN koth_players p2 ON
                p2.id_game = g.id
            AND p2.id_user = {$this->idUser}
    INNER JOIN koth_die_types dt ON
        dt.name = $type
SET
    pd.value       = $value,
    pd.id_die_type = dt.id,
    pd.keep        = 1
WHERE
    pd.id = $id
EOD;
            $queries[] = $query;
        }
        
        foreach ( $queries as $query )
        {
            $this->query($query);
        }
    }
}
