<?php

class Koth_LIB_Game_Model extends Base_LIB_Model
{
    // private data
    private $idGame           = 0;
    private $idUser           = 0;
    private $idOtherUser      = 0;
    private $idActiveUser     = 0;
    private $idInactiveUser   = 0;
    private $idUserPlayer     = 0;
    private $idOtherPlayer    = 0;
    private $idActivePlayer   = 0;
    private $idInactivePlayer = 0;

    // Only usefull for PvE/PvP
    private function computeIds( $idGame = null )
    {
        if ( !$idGame )
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

            // No active game for user
            if ( !$result )
            {
                return;
            }
            
            // Game id
            $this->idGame = $this->getQuotedValue( 0 + $result->id_game );
        }

        // Other user
        $this->query("SELECT id_user FROM koth_players WHERE id_game = {$this->idGame} AND id_user != {$this->idUser}");
        if ( $result = $this->fetchNext() )
        {
            $this->idOtherUser = $this->getQuotedValue( 0 + $result->id_user );
        }

        // Active user
        $this->query("  SELECT
                            p.id_user id_user 
                        FROM
                            koth_players p 
                            INNER JOIN koth_games g ON
                                g.id = p.id_game
                            AND g.id_active_player = p.id 
                        WHERE
                            p.id_game = {$this->idGame} ");
        $this->idActiveUser = $this->getQuotedValue( 0 + $this->fetchNext()->id_user );

        // Inactive user
        $this->query("SELECT p.id_user id_user FROM koth_players p INNER JOIN koth_games g ON g.id = p.id_game AND g.id_active_player != p.id WHERE p.id_game = {$this->idGame} ");
        $this->idInactiveUser = $this->getQuotedValue( 0 + $this->fetchNext()->id_user );

        // User player
        $this->query("SELECT id FROM koth_players WHERE id_game = {$this->idGame} AND id_user = {$this->idUser}");
        $this->idUserPlayer = $this->getQuotedValue( 0 + $this->fetchNext()->id );

        // Other player
        $this->query("SELECT id FROM koth_players WHERE id_game = {$this->idGame} AND id_user != {$this->idUser}");
        $this->idOtherPlayer = $this->getQuotedValue( 0 + $this->fetchNext()->id );

        // Active player
        $this->query("SELECT id_active_player FROM koth_games WHERE id = {$this->idGame} ");
        $this->idActivePlayer = $this->getQuotedValue( 0 + $this->fetchNext()->id_active_player );

        // Inactive player
        $this->query("SELECT id FROM koth_players WHERE id_game = {$this->idGame} AND id != {$this->idActivePlayer}");
        $this->idInactivePlayer = $this->getQuotedValue( 0 + $this->fetchNext()->id );
    }

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

    public function getStep()
    {
        $this->query("SELECT s.name  step FROM koth_steps s INNER JOIN koth_games g ON g.id_step = s.id AND g.id = {$this->idGame}");
        return $this->fetchNext()->step;
    }

    public function getResults()
    {
        $query = <<<EOD
SELECT
    pw.id_user              id_winner_user,
    g.xp_winning_player     xp_winner,
    g.xp_losing_player      xp_loser,
    pw.current_hp           hp_winner,
    pl.current_hp           hp_loser,
    pw.current_mp           mp_winner,
    pl.current_mp           mp_loser
FROM
    koth_games g
    INNER JOIN koth_players pw ON
        pw.id = g.id_winning_player
    INNER JOIN koth_players pl ON
        pl.id = g.id_losing_player
WHERE
    g.id = {$this->idGame}
EOD;
        $this->query($query);
        return $this->fetchNext();
    }

    public function getLevels()
    {
        $this->query("SELECT pa.level + pi.level levels FROM koth_players pa, koth_players pi WHERE pa.id = {$this->idActivePlayer} AND pi.id = {$this->idInactivePlayer}");
        return $this->fetchNext()->levels;
    }

    private function getAILevel()
    {
        $query = <<<EOD
SELECT
    o.ai_level  ai_level
FROM
    koth_opponents o
    INNER JOIN koth_players p ON
        p.id_opponent = o.id
    AND p.id          = {$this->idActivePlayer}
EOD;
        $this->query($query);
        return $this->fetchNext()->ai_level;
    }

    // EvE if no users
    public function isEvE()
    {
        return !$this->idActiveUser && !$this->idInactiveUser;
    }

    // PvP if two users
    public function isPvP()
    {
        return $this->idActiveUser && $this->idInactiveUser;
    }

    // PvE if not EvE and not PvP
    public function isPvE()
    {
        return !$this->isPvP() && !$this->isEvE();
    }

    public function isActiveAI()
    {
        return ( $this->idActiveUser == 0 );
    }

    public function isUserActive()
    {
        return ( $this->idUser == $this->idActiveUser );
    }

    public function getIdActivePlayer()
    {
        return $this->idActivePlayer;
    }

    // Active player's magic is over the threshold
    // OR non-active player's health is 0 or less
    public function isVictory()
    {
        $magicWin = Koth_LIB_Game::getMagicThreshold();

        $query = <<<EOD
SELECT
    COUNT(1)    is_victory
FROM
    koth_players pa,
    koth_players pi
WHERE
    pa.id = {$this->idActivePlayer}
AND pi.id = {$this->idInactivePlayer}
AND (  ( pa.current_mp >= $magicWin )
     OR( pi.current_hp < 1 )  )
EOD;
        $this->query($query);

        return ( $this->fetchNext()->is_victory ? true : false );
    }

    public function isGameActiveForUser()
    {
        return $this->idGame;
    }

    // TBD: id_opponent
    public function startGameEvE( $heroName = 'cleric', $heroLevel = 3, $opponentName = '5_3_3_3_1' )
    {
        $heroName     = $this->getQuotedValue($heroName);
        $heroLevel    = $this->getQuotedValue( 0 + $heroLevel );
        $opponentName = $this->getQuotedValue($opponentName);

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
        $heroRank     = rand(1, 2);
        $opponentRank = 3 - $heroRank;

        $heroRank     = $this->getQuotedValue($heroRank);
        $opponentRank = $this->getQuotedValue($opponentRank);

        // Create Hero AI
        $query = <<<EOD
INSERT INTO
    koth_players (id_user, id_game, id_hero, level, current_mp, current_hp, current_xp, rank )
SELECT
    0,
    $idGame,
    h.id,
    hl.level,
    hl.start_mp,
    hl.start_hp,
    hl.start_xp,
    $heroRank
FROM
    koth_heroes h
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = h.id
    AND hl.level   = $heroLevel
WHERE
    h.name = $heroName
EOD;
        $this->query($query);
        
        // Create Opponent AI
        $query = <<<EOD
INSERT INTO
    koth_players (id_user, id_game, id_opponent, level, current_mp, current_hp, current_xp, rank )
SELECT
    0,
    $idGame,
    id,
    level,
    start_mp,
    start_hp,
    start_xp,
    $opponentRank
FROM
    koth_opponents
WHERE
    name = $opponentName
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

        // TBD: compute ids for EvE
        // Very important!
    }

    public function startGamePvE( $heroName = 'cleric', $heroLevel = 3, $opponentName = '5_3_3_3_1' )
    {
        $heroName     = $this->getQuotedValue($heroName);
        $heroLevel    = $this->getQuotedValue( 0 + $heroLevel );
        $opponentName = $this->getQuotedValue($opponentName);

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
        $heroRank     = rand(1, 2);
        $opponentRank = 3 - $heroRank;

        $heroRank     = $this->getQuotedValue($heroRank);
        $opponentRank = $this->getQuotedValue($opponentRank);

        // Create hero for user
        $query = <<<EOD
INSERT INTO
    koth_players (id_user, id_game, id_hero, level, current_mp, current_hp, current_xp, rank )
SELECT
    {$this->idUser},
    $idGame,
    h.id,
    hl.level,
    hl.start_mp,
    hl.start_hp,
    hl.start_xp,
    $heroRank
FROM
    koth_heroes h
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = h.id
    AND hl.level   = $heroLevel
WHERE
    h.name = $heroName
EOD;
        $this->query($query);
        
        // Create opponent for AI
        $query = <<<EOD
INSERT INTO
    koth_players (id_user, id_game, id_opponent, level, current_mp, current_hp, current_xp, rank )
SELECT
    0,
    $idGame,
    id,
    level,
    start_mp,
    start_hp,
    start_xp,
    $opponentRank
FROM
    koth_opponents
WHERE
    name = $opponentName
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

        // Update this object fields
        $this->computeIds( $idGame );
    }

    public function roll( $isAI = false )
    {
        // Get max values for the different types
        if ( $isAI )
        {
            $query = <<<EOD
SELECT
    o.max_attack       max_attack,
    o.max_health       max_health,
    o.max_experience   max_experience,
    o.max_magic        max_magic
FROM
    koth_opponents o
    INNER JOIN koth_players p ON
        p.id_opponent = o.id
    AND p.id          = {$this->idActivePlayer}
EOD;
        }
        else
        {
            $query = <<<EOD
SELECT
    hl.max_attack       max_attack,
    hl.max_health       max_health,
    hl.max_experience   max_experience,
    hl.max_magic        max_magic
FROM
    koth_heroes_levels hl
    INNER JOIN koth_players p ON
        p.id_hero    = hl.id_hero
    AND p.level = hl.level
    AND p.id         = {$this->idActivePlayer}
EOD;
        }

        $this->query($query);
        $max = $this->fetchNext();

        $maxes = array( 'attack'     => $max->max_attack,
                        'health'     => $max->max_health,
                        'experience' => $max->max_experience,
                        'magic'      => $max->max_magic );

        // Number of dice to reroll for active player
        $this->query("SELECT COUNT(1) dice_number FROM koth_players_dice WHERE keep = 0 AND id_player = {$this->idActivePlayer}");
        $diceNumberToRoll = $this->fetchNext()->dice_number;

        // Compute the new dice values
        $newDice = array();
        for ( $i = 0; $i < $diceNumberToRoll; $i++ )
        {
            $type  = array_rand( $maxes );
            $value = $maxes[ $type ] - rand(0, 2);

            $newDice[] = array( 'type' => $type, 'value' => $value );
        }

        // Update dice with new values
        // ---------------------------
        // First select ids
        $this->query("SELECT id FROM koth_players_dice WHERE keep = 0 AND id_player = {$this->idActivePlayer}");

        // Creating queries
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
        
        // Actually doing the work: updating dice
        foreach ( $queries as $query )
        {
            $this->query($query);
        }

        // Update game step
        $this->query("UPDATE koth_games SET id_step = id_step + 1 WHERE id = {$this->idGame}");
    }

    // For AI to compute dice to keep
    public function keepDiceAI( $rollLeft = 2 )
    {
        // AI level 0 : nothing to do (duh!)
        if ( ( $aiLevel = $this->getAILevel() ) == 0 )
        {
            return;
        }

        if ( $rollLeft == 2 )
        {
            $diceNumberToReroll = 8;
        }
        else if ( $rollLeft == 1 )
        {
            $diceNumberToReroll = 6;
        }

        // Get die distribution
        $query = <<<EOD
SELECT
    o.max_attack        attack,
    o.max_health        health,
    o.max_experience    experience,
    o.max_magic         magic
FROM
    koth_opponents o
    INNER JOIN koth_players p ON
        p.id_opponent = o.id
    AND p.id          = {$this->idActivePlayer}
EOD;
        $this->query($query);
        $maxes = $this->fetchNext();

        // First create non-sorted distribution
        $distribution = array();
        foreach ( $maxes as $type => $max )
        {
            $distribution[] = array( 'type' => $type, 'value' => $max );
            $distribution[] = array( 'type' => $type, 'value' => $max - 1 );
            $distribution[] = array( 'type' => $type, 'value' => $max - 2 );
        }
        // AI level 1 : Sort distrib from worst to best by value
        usort($distribution, function( $a, $b ) { return $a['value'] > $b['value']; } );

        if ( $this->getAILevel() >= 2 )
        {
            // TBD: Health management : high => worst, low => best
            // TBD: Xp management: get a die => good, early game => good, late game => bad, more dice => bad
            // TBD: oriented attack/magic, rush, etc...
        }

        $diceToReroll = array_slice( $distribution, 0, $diceNumberToReroll );

        $dieCdt = array();
        foreach ( $diceToReroll as $die )
        {
            $dieCdt[] = '( pd.value = ' . $this->getQuotedValue( 0 + $die['value'] ) . ' AND dt.name = ' . $this->getQuotedValue( $die['type'] ) . ' )';
        }
        $dieCdt = implode(' OR ', $dieCdt);

        $query = <<<EOD
UPDATE
koth_players_dice pd
INNER JOIN koth_die_types dt ON
    dt.id = pd.id_die_type
SET
pd.keep = 0
WHERE
pd.id_player = {$this->idActivePlayer}
AND (  $dieCdt  )
EOD;
        $this->query($query);
    }

    public function processEndTurn()
    {
        // Update turn number
        $query = <<<EOD
UPDATE
    koth_games g
    INNER JOIN koth_players p ON
        p.id   = g.id_active_player
    AND p.rank = 2
SET
    g.turn_number = g.turn_number + 1
WHERE
    g.id = {$this->idGame}
EOD;
        $this->query($query);

        // Change active player
        $this->query("UPDATE koth_games SET id_active_player = {$this->idInactivePlayer} WHERE id = {$this->idGame} ");

        // Update active/inactive ids
        list( $this->idActivePlayer,
              $this->idInactivePlayer,
              $this->idActiveUser,
              $this->idInactiveUser ) = array( $this->idInactivePlayer,
                                               $this->idActivePlayer,
                                               $this->idInactiveUser,
                                               $this->idActiveUser );

        // Reset active player's dice to unknown / don't keep
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

        // change step to start turn
        $this->query("UPDATE koth_games g INNER JOIN koth_steps s ON s.name = 'start_turn' SET g.id_step = s.id WHERE g.id = {$this->idGame} ");
    }

    // TBD: add a new currency like gold ?
    private function completeGame( $idWinningPlayer, $idLosingPlayer, $isConcede = false )
    {
        // Update winning/losing users in game
        $this->query("UPDATE koth_games SET id_winning_player = {$idWinningPlayer}, id_losing_player = {$idLosingPlayer} WHERE id = {$this->idGame}");

        $divide = ( $isConcede ? ' / 2 ' : '' );
        
        // Update game xp for winning/losing from game_xp on players
        // Twice more experience for winning player
        $query = <<<EOD
UPDATE
    koth_games g
    INNER JOIN koth_players pw ON
        pw.id = {$idWinningPlayer}
    INNER JOIN koth_players pl ON
        pl.id = {$idLosingPlayer}
SET
    g.xp_winning_player = g.xp_winning_player + 2 * pw.game_xp,
    g.xp_losing_player  = g.xp_losing_player  + pl.game_xp $divide
WHERE
    g.id = {$this->idGame}
EOD;
        $this->query($query);

        // Winning user
        $query = <<<EOD
UPDATE
    koth_user_xp ux
    INNER JOIN koth_players p ON
        p.id_user = ux.id_user
    INNER JOIN koth_games g ON
        g.id_winning_player = p.id
    AND g.id                = {$this->idGame}
SET
    ux.experience = ux.experience + g.xp_winning_player
EOD;
        $this->query($query);

        // Losing user
        $query = <<<EOD
UPDATE
    koth_user_xp ux
    INNER JOIN koth_players p ON
        p.id_user = ux.id_user
    INNER JOIN koth_games g ON
        g.id_losing_player = p.id
    AND g.id                = {$this->idGame}
SET
    ux.experience = ux.experience + g.xp_losing_player
EOD;
        $this->query($query);

        // Winning Hero
        $query = <<<EOD
UPDATE
    koth_users_heroes uh
    INNER JOIN koth_players p ON
        p.id_hero = uh.id_hero
    AND p.id_user = uh.id_user
    INNER JOIN koth_games g ON
        g.id_winning_player = p.id
    AND g.id              = {$this->idGame}
SET
    uh.experience = uh.experience + g.xp_winning_player
EOD;
        $this->query($query);

        // Losing Hero
        $query = <<<EOD
UPDATE
    koth_users_heroes uh
    INNER JOIN koth_players p ON
        p.id_hero = uh.id_hero
    AND p.id_user = uh.id_user
    INNER JOIN koth_games g ON
        g.id_losing_player = p.id
    AND g.id              = {$this->idGame}
SET
    uh.experience = uh.experience + g.xp_losing_player
EOD;
        $this->query($query);

        // Update user level
        // TBD: securise potential bug when user gain more than one level
        $query = <<<EOD
UPDATE
    koth_user_xp ux
    INNER JOIN koth_user_xp_level uxl ON
        uxl.level      = ux.level
    AND uxl.threshold <= ux.experience
SET
    ux.level      = ( ux.level + 1 ),
    ux.experience = ( ux.experience - uxl.threshold )
WHERE
    ux.id_user = {$this->idUser}
OR  ux.id_user = {$this->idOtherUser}
EOD;
        $this->query($query);

        // Update hero level
        $query = <<<EOD
UPDATE
    koth_users_heroes uh
    INNER JOIN koth_hero_xp_level hxl ON
        hxl.level      = uh.level
    AND hxl.threshold <= uh.experience
    INNER JOIN koth_players p ON
        p.id_user = uh.id_user
    AND p.id_game = {$this->idGame}
    AND p.id_hero = uh.id_hero
SET
    uh.level      = ( uh.level + 1 ),
    uh.experience = ( uh.experience - hxl.threshold )
WHERE
    uh.id_user = {$this->idUser}
OR  uh.id_user = {$this->idOtherUser}
EOD;
        $this->query($query);

        // Remove active and inactive players dice
        $this->query("DELETE FROM koth_players_dice WHERE id_player = {$this->idActivePlayer} OR id_player = {$this->idInactivePlayer}");

        // Update game step
        $this->query("UPDATE koth_games g INNER JOIN koth_steps s ON s.name = 'game_finished' SET g.id_step = s.id WHERE g.id = {$this->idGame}");
    }

    // Active player win the game
    public function activeWinGame()
    {
        // Finish closing the game
        $this->completeGame( $this->idActivePlayer /* winner */, $this->idInactivePlayer );
    }

    // User lose the game by conceding
    public function userConcedeGame()
    {
        // Finish closing the game
        $this->completeGame( $this->idOtherPlayer /* winner */, $this->idUserPlayer, true /* concede */ );
    }

    // Update game is_completed
    public function closeGame()
    {
        $this->query("UPDATE koth_games SET is_completed = 1 WHERE id = {$this->idGame} ");
    }
}
