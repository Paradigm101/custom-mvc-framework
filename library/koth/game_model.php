<?php

class Koth_LIB_Game_Model extends Base_LIB_Model
{
    private $idUser = 0;
    private $idGame = 0;
    private $idUserPlayer = 0;
    private $idOtherPlayer = 0;
    private $idOtherUser = 0;
    private $idActivePlayer = 0;
    private $idInactivePlayer = 0;
    private $idActiveUser = 0;
    private $idInactiveUser = 0;

    public function __construct( $idUser )
    {
        parent::__construct();
        
        $this->idUser = $this->getQuotedValue( 0 + $idUser );
        
        // Get ids
        if ( $this->idUser )
        {
            $this->computeIds();
        }
    }

    // Sort dice pool and return best dice
    private function aiGetBestDice( $num )
    {
        // TBD: need dice selection
        // TBD: need to know current health to remove hp dice if needed
        // TBD: need to know current Xp to remove xp dice if needed

        $sortedDice = array( array( 'type' => 'attack',     'value' => 3 ),
                             array( 'type' => 'experience', 'value' => 3 ),
                             array( 'type' => 'health',     'value' => 3 ),
                             array( 'type' => 'victory',    'value' => 3 ),
                             array( 'type' => 'attack',     'value' => 2 ),
                             array( 'type' => 'experience', 'value' => 2 ),
                             array( 'type' => 'health',     'value' => 2 ),
                             array( 'type' => 'victory',    'value' => 2 ),
                             array( 'type' => 'attack',     'value' => 1 ),
                             array( 'type' => 'experience', 'value' => 1 ),
                             array( 'type' => 'health',     'value' => 1 ),
                             array( 'type' => 'victory',    'value' => 1 ) );

        return array_slice( $sortedDice, 0, $num );
    }

    // TBD: use dice selection to unkeep dice
    public function keepDiceAI( $rollLeft = 2 )
    {
        switch ( $rollLeft )
        {
            case 2:
                $diceToKeep = $this->aiGetBestDice( 4 );
                $this->query("UPDATE koth_players_dice SET keep = 0 WHERE value < 3 AND id_player = {$this->idActivePlayer}");
                break;
            case 1:
                $diceToKeep = $this->aiGetBestDice( 6 );
                $this->query("UPDATE koth_players_dice SET keep = 0 WHERE value < 2 AND id_player = {$this->idActivePlayer}");
                break;
            default:
                break;
        }
    }

    private function computeIds()
    {
        // Get game id
        $query = <<<EOD
SELECT
    g.id    id_game
FROM
    koth_games g
INNER JOIN koth_players p ON
    p.id_game = g.id
AND p.id_user = {$this->idUser}
WHERE
    g.is_completed = 0
EOD;
        $this->query($query);
        $result = $this->fetchNext();

        if ( $result )
        {
            // Game id
            $this->idGame = $this->getQuotedValue( 0 + $result->id_game );

            // User player
            $this->query("SELECT id FROM koth_players WHERE id_game = {$this->idGame} AND id_user = {$this->idUser}");
            $this->idUserPlayer = $this->getQuotedValue( 0 + $this->fetchNext()->id );

            // Other player
            $this->query("SELECT id FROM koth_players WHERE id_game = {$this->idGame} AND id_user != {$this->idUser}");
            $this->idOtherPlayer = $this->getQuotedValue( 0 + $this->fetchNext()->id );

            // Other user
            $this->query("SELECT id_user FROM koth_players WHERE id_game = {$this->idGame} AND id = {$this->idOtherPlayer}");
            $this->idOtherUser = $this->getQuotedValue( 0 + $this->fetchNext()->id_user );

            // Active player
            $this->query("SELECT id_active_player FROM koth_games WHERE id = {$this->idGame} ");
            $this->idActivePlayer = $this->getQuotedValue( 0 + $this->fetchNext()->id_active_player );

            // Inactive player
            $this->query("SELECT id FROM koth_players WHERE id_game = {$this->idGame} AND id != {$this->idActivePlayer}");
            $this->idInactivePlayer = $this->getQuotedValue( 0 + $this->fetchNext()->id );
            
            // Active user
            $this->query("SELECT p.id_user id_user FROM koth_players p INNER JOIN koth_games g ON g.id = p.id_game AND g.id_active_player = p.id WHERE p.id_game = {$this->idGame} ");
            $this->idActiveUser = $this->getQuotedValue( 0 + $this->fetchNext()->id_user );
            
            // Inactive user
            $this->query("SELECT p.id_user id_user FROM koth_players p INNER JOIN koth_games g ON g.id = p.id_game AND g.id_active_player != p.id WHERE p.id_game = {$this->idGame} ");
            $this->idInactiveUser = $this->getQuotedValue( 0 + $this->fetchNext()->id_user );

        }
    }
    
    public function isPvE()
    {
        return !$this->idOtherUser;
    }

    public function isPvP()
    {
        return $this->idOtherUser;
    }

    public function getStep()
    {
        $this->query("SELECT s.name  step FROM koth_steps s INNER JOIN koth_games g ON g.id_step = s.id AND g.id = {$this->idGame}");
        return $this->fetchNext()->step;
    }

    public function isUserActive()
    {
        return ( $this->idUserPlayer && ( $this->idUserPlayer == $this->idActivePlayer ) );
    }

    public function stepStart()
    {
        $this->query("UPDATE koth_games g INNER JOIN koth_steps s ON s.name = 'start_turn' SET g.id_step = s.id WHERE g.id = {$this->idGame} ");
    }
    
    // add any number of unknown dice for active player
    private function addUnknownDice( $num = 0 )
    {
        // No dice to add
        if ( $num == 0 )
        {
            return;
        }

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
    INNER JOIN koth_die_types dt ON
        dt.name = 'unknown'
WHERE
    p.id = {$this->idActivePlayer}
EOD;
        for( $i = 0; $i < $num; $i++ )
        {
            $this->query($query);
        }
    }

    // Get number of dice for active player
    private function getDicePoolNumber() {

        $query = <<<EOD
SELECT
    COUNT(1)    num
FROM
    koth_players_dice pd
WHERE
    pd.id_player = {$this->idActivePlayer}
EOD;
        $this->query($query);
        return $this->fetchNext()->num;
    }

    // Reset active player's dice
    // Set dice number to 6 if needed
    public function resetDice()
    {
        $query = <<<EOD
UPDATE
    koth_players_dice pd
    INNER JOIN koth_die_types dt ON
        dt.name = 'unknown'
SET
    pd.id_die_type = dt.id,
    pd.value       = 0,
    pd.keep        = 0
WHERE
    pd.id_player = {$this->idActivePlayer}
EOD;
        $this->query($query);
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
        // Update DB
        $this->query("UPDATE koth_games SET id_active_player = {$this->idInactivePlayer} WHERE id = {$this->idGame} ");

        // Update ids (probably useless)
        $this->computeIds();
    }

    // Active player's victory over threshold
    // OR non-active player's health is 0 or less
    public function isVictory()
    {
        $victoryWin = KOTH_VICTORY_WIN;

        $query = <<<EOD
SELECT
    COUNT(1)    is_victory
FROM
    koth_players pa,
    koth_players pi
WHERE
    pa.id = {$this->idActivePlayer}
AND pi.id = {$this->idInactivePlayer}
AND (  ( pa.current_vp >= $victoryWin ) 
     OR( pi.current_hp < 1 )  )
EOD;
        $this->query($query);

        return ( $this->fetchNext()->is_victory ? true : false );
    }

    private function closeGame( $idWinningUser, $idLosingUser )
    {
        // Update winning/losing users in game
        $this->query("UPDATE koth_games SET id_winning_user = {$idWinningUser}, id_losing_user = {$idLosingUser} WHERE id = {$this->idGame}");

        // Update game xp for winning/losing from game_xp on players
        $query = <<<EOD
UPDATE
    koth_games g
    INNER JOIN koth_players pw ON
        pw.id_game = g.id
    AND pw.id_user = {$idWinningUser}
    INNER JOIN koth_players pl ON
        pl.id_game = g.id
    AND pl.id_user = {$idLosingUser}
SET
    g.xp_winning_user = g.xp_winning_user + pw.game_xp,
    g.xp_losing_user  = g.xp_losing_user  + pl.game_xp
WHERE
    g.id = {$this->idGame}
EOD;
        $this->query($query);

        // Update Winner xp on users'heroes and users from game table
        $query = <<<EOD
UPDATE
    koth_user_xp ux
    INNER JOIN koth_games g ON
        g.id_winning_user = ux.id_user
    AND g.id              = {$this->idGame}
SET
    ux.experience = ux.experience + g.xp_winning_user
EOD;
        $this->query($query);

        $query = <<<EOD
UPDATE
    koth_users_heroes uh
    INNER JOIN koth_games g ON
        g.id_winning_user = uh.id_user
    AND g.id              = {$this->idGame}
        INNER JOIN koth_players p ON
            p.id_game = g.id
        AND p.id_user = g.id_winning_user
SET
    uh.experience = uh.experience + g.xp_winning_user
WHERE
    uh.id_hero = p.id_hero
EOD;
        $this->query($query);

        // Update Loser xp on users'heroes and users from game table
        $query = <<<EOD
UPDATE
    koth_user_xp ux
    INNER JOIN koth_games g ON
        g.id_losing_user = ux.id_user
    AND g.id             = {$this->idGame}
SET
    ux.experience = ux.experience + g.xp_losing_user
EOD;
        $this->query($query);

        $query = <<<EOD
UPDATE
    koth_users_heroes uh
    INNER JOIN koth_games g ON
        g.id_losing_user = uh.id_user
    AND g.id              = {$this->idGame}
        INNER JOIN koth_players p ON
            p.id_game = g.id
        AND p.id_user = g.id_winning_user
SET
    uh.experience = uh.experience + g.xp_losing_user
WHERE
    uh.id_hero = p.id_hero
EOD;
        $this->query($query);

        // TBD: Manage user/hero levels
        
        // Remove active and inactive players dice
        $this->query("DELETE FROM koth_players_dice WHERE id_player = {$this->idActivePlayer} OR id_player = {$this->idInactivePlayer}");

        // Update ids (probably useless)
        $this->computeIds();
    }

    // Update game is_completed
    public function setCompleted()
    {
        $this->query("UPDATE koth_games SET is_completed = 1 WHERE id = {$this->idGame} ");
    }

    // Active player win the game
    public function activeWinGame()
    {
        // TBD update game xp for winning/losing with extra X%/Y% for winner/loser
        //      or maybe gold or other

        // Finish closing the game
        $this->closeGame( $this->idActiveUser /* winner */, $this->idInactiveUser );
    }

    // User lose the game by conceding
    public function userConcedeGame()
    {
        // Finish closing the game
        $this->closeGame( $this->idOtherUser /* winner */, $this->idUser );
    }

    // health add to active player
    // experience add to active player
    // victory points add to active player
    // attack remove health from non-active player
    // update game_xp for active player
    public function storeResults( $results )
    {
        $experience = $this->getQuotedValue( 0 + ( array_key_exists('experience', $results) ? $results['experience'] : 0 ) );
        $victory    = $this->getQuotedValue( 0 + ( array_key_exists('victory', $results) ? $results['victory'] : 0 ) );
        $health     = $this->getQuotedValue( 0 + ( array_key_exists('health', $results) ? $results['health'] : 0 ) );
        $attack     = $this->getQuotedValue( 0 + ( array_key_exists('attack', $results) ? $results['attack'] : 0 ) );
        $gameXp     = $this->getQuotedValue( 0 + $experience + $victory + $health + $attack );

        // Update active player's health/victory/xp
        $query = <<<EOD
UPDATE
    koth_players p
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = p.id_hero
    AND hl.level   = 1
SET
    p.current_xp = p.current_xp + $experience,
    p.current_vp = p.current_vp + $victory,
    p.current_hp = LEAST( p.current_hp + $health, hl.start_hp ),
    p.game_xp    = p.game_xp + $gameXp
WHERE
    p.id = {$this->idActivePlayer}
EOD;
        $this->query($query);

        // Update inactive player's health
        $this->query("UPDATE koth_players SET current_hp = current_hp - $attack WHERE id = {$this->idInactivePlayer}");
        
        // Convert xp into something
        $this->query("SELECT current_xp FROM koth_players WHERE id = {$this->idActivePlayer}");
        $current_xp = $this->fetchNext()->current_xp;

        // Every 15 xp, user get a new die
        $this->addUnknownDice( KOTH_STARTING_DICE + floor( $current_xp / 15 ) - $this->getDicePoolNumber() );
    }

    public function isGameActive()
    {
        return $this->idGame;
    }

    // TBD: manage different hero/level
    public function startGame()
    {
        // If a game is already active, do nothing
        // Front will refresh page and user will see current game
        // User can concede to start a new game
        if ( $this->idGame )
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

        // Create user player
        // TBD: Manage hero + hero level
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
EOD;
        $this->query($query);
        
        // Create other player
        // TBD: Manage hero + hero level
        // TBD: Manage PvP
        $query = <<<EOD
INSERT INTO
    koth_players (id_user, id_game, id_hero, hero_level, current_vp, current_hp, current_xp, rank )
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
    koth_players_dice (
        id_player,
        id_die_type,
        keep,
        value
    )
SELECT
    p.id,
    dt.id,
    0,
    0
FROM
    koth_players p
    INNER JOIN koth_die_types dt ON
        dt.name = 'unknown'
WHERE
    p.rank    = 1
AND p.id_game = $idGame
EOD;
        for ( $i = 0; $i < floor( KOTH_STARTING_DICE / 2 ); $i++ )
        {
            $this->query($query);
        }

        // Insert starting dice for non-active player
        $query = <<<EOD
INSERT INTO
    koth_players_dice (
        id_player,
        id_die_type,
        keep,
        value
    )
SELECT
    p.id,
    dt.id,
    0,
    0
FROM
    koth_players p
    INNER JOIN koth_die_types dt ON
        dt.name = 'unknown'
WHERE
    p.rank    = 2
AND p.id_game = $idGame
EOD;
        for ( $i = 0; $i < KOTH_STARTING_DICE; $i++ )
        {
            $this->query($query);
        }

        // Update this object fields (probably useless)
        $this->computeIds();
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
    AND p.id         = {$this->idActivePlayer}
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
        $this->query("UPDATE koth_games SET id_step = id_step + 1 WHERE id = {$this->idGame}");
    }

    public function updateDice( $newDice )
    {
        // First select ids
        $this->query("SELECT id FROM koth_players_dice WHERE keep = 0 AND id_player = {$this->idActivePlayer}");

        $queries = array();
        foreach ( $newDice as $die )
        {
            $type  = $this->getQuotedValue( $die['type'] );
            $value = $this->getQuotedValue( 0 + $die['value'] );
            $id    = $this->fetchNext()->id;

            $query = <<<EOD
UPDATE
    koth_players_dice pd
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
    
    public function setGameFinished()
    {
        $this->query("UPDATE koth_games g INNER JOIN koth_steps s ON s.name = 'game_finished' SET g.id_step = s.id WHERE g.id = {$this->idGame}");
    }
}
