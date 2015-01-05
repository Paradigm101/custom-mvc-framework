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

    private function computeIds()
    {
        // Current user
        $this->idUser = $this->getQuotedValue( 0 + Session_LIB::getUserId() );

        // No clue about the game, check if there is one for the current user
        if ( !$this->idGame )
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

            // No active game for user, nothing IDs to recompute
            if ( !$result )
            {
                return;
            }

            // Game id
            $this->idGame = $this->getQuotedValue( 0 + $result->id_game );
        }

        // Get ids for active/inactive
        $query = <<<EOD
SELECT
    pa.id       id_active_player,
    pa.id_user  id_active_user,
    pi.id       id_inactive_player,
    pi.id_user  id_inactive_user
FROM
    koth_games g
    INNER JOIN koth_players pa ON
        pa.id_game = g.id
    AND pa.id      = g.id_active_player
    INNER JOIN koth_players pi ON
        pi.id_game  = g.id
    AND pi.id      != g.id_active_player
WHERE
    g.id = {$this->idGame}
EOD;
        $this->query($query);
        $result = $this->fetchNext();

        if ( $result )
        {
            $this->idActivePlayer   = $result->id_active_player;
            $this->idInactivePlayer = $result->id_inactive_player;
            $this->idActiveUser     = $result->id_active_user;
            $this->idInactiveUser   = $result->id_inactive_user;
        }

        // Get ids for user/other
        $query = <<<EOD
SELECT
    pu.id       id_user_player,
    po.id       id_other_player,
    po.id_user  id_other_user
FROM
    koth_players pu,
    koth_players po
WHERE
    pu.id_user  = {$this->idUser}
AND pu.id_game  = {$this->idGame}
AND po.id_user != {$this->idUser}
AND po.id_game  = {$this->idGame}
EOD;
        $this->query($query);
        $result = $this->fetchNext();

        if ( $result )
        {
            $this->idUserPlayer  = $result->id_user_player;
            $this->idOtherPlayer = $result->id_other_player;
            $this->idOtherUser   = $result->id_other_user;
        }
    }

    public function __construct( $idUser )
    {
        parent::__construct();

        $this->computeIds();
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
    g.id                    idGame,
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
    koth_monsters o
    INNER JOIN koth_players p ON
        p.id_monster = o.id
    AND p.id          = {$this->idActivePlayer}
EOD;
        $this->query($query);
        return $this->fetchNext()->ai_level;
    }

    // EvE if no users (and an active game is running)
    public function isEvE()
    {
        return !$this->idActiveUser && !$this->idInactiveUser && $this->idGame;
    }

    // PvP if two users
    public function isPvP()
    {
        return $this->idActiveUser && $this->idInactiveUser;
    }

    // PvE if not EvE and not PvP (and an active game is running)
    public function isPvE()
    {
        return !$this->isPvP() && !$this->isEvE()  && $this->idGame;
    }

    public function isActiveAI()
    {
        return ( $this->idActiveUser == 0 );
    }

    public function isUserActive()
    {
        return ( $this->idUser == $this->idActiveUser );
    }

    public function isUserInactive()
    {
        return ( $this->idUser == $this->idInactiveUser );
    }

    public function isUserPlaying()
    {
        return $this->isUserActive() || $this->isUserInactive();
    }

    public function getIdUserPlayer()
    {
        return $this->idUserPlayer;
    }

    public function getIdOtherPlayer()
    {
        return $this->idOtherPlayer;
    }

    public function getIdPlayersByRank()
    {
        $this->query("SELECT id FROM koth_players WHERE id_game = {$this->idGame} ORDER BY rank");
        $results = $this->fetchAll();
        return array( $results[0]->id, $results[1]->id );
    }

    public function getIdActivePlayer()
    {
        return $this->idActivePlayer;
    }

    public function getIdInactivePlayer()
    {
        return $this->idInactivePlayer;
    }

    public function isGameCompleted()
    {
        $this->query("SELECT is_completed FROM koth_games WHERE id = {$this->idGame}");
        return ( $this->fetchNext()->is_completed ? true : false );
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

    // Game is finished and user has to acknowledge the score
    public function isGameScoreForUser()
    {
        $idUser = $this->getQuotedValue( 0 + Session_LIB::getUserId() );

        $this->query("SELECT g.id FROM koth_games g INNER JOIN koth_players p ON p.id_user = $idUser AND p.id_game = g.id AND p.ack_score = 0 WHERE g.is_completed = 1");
        $result = $this->fetchNext();
        
        return ( $result ? true : false );
    }

    public function isGameActiveForUser()
    {
        return $this->idGame;
    }

    public function setGameForScore()
    {
        $idUser = $this->getQuotedValue( 0 + Session_LIB::getUserId() );

        $this->query("SELECT g.id FROM koth_games g INNER JOIN koth_players p ON p.id_user = $idUser AND p.id_game = g.id AND p.ack_score = 0 WHERE g.is_completed = 1");
        $result = $this->fetchNext();

        if ( $result )
        {
            $this->idGame = $result->id;

            // Update this object fields
            $this->computeIds();
        }
    }

    public function isPlayingPvP( $idUser )
    {
        $idUser = $this->getQuotedValue($idUser + 0);

        // Check user not already playing pvp
        $query = <<<EOD
SELECT
    COUNT(1) is_playing
FROM
    koth_players p
    INNER JOIN koth_games g ON
        g.id           = p.id_game
    AND g.is_completed = 0
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
        AND p2.id_user != $idUser
        AND p2.id_user != 0
WHERE
    p.id_user = $idUser
EOD;
        $this->query($query);
        return ( $this->fetchNext()->is_playing ? true : false );
    }

    public function isQueuedInRandomPvP( $idUser )
    {
        $idUser = $this->getQuotedValue( $idUser + 0 );

        $this->query("SELECT id_user FROM koth_random_pvp WHERE id_user = $idUser ");
        return ( $this->fetchNext() ? true : false );
    }

    public function isQueuedInHeroPvP( $idUser, $idHero )
    {
        $idUser = $this->getQuotedValue( $idUser + 0 );
        $idHero = $this->getQuotedValue( $idHero + 0 );

        $query = <<<EOD
SELECT
    COUNT(1)    is_queued
FROM
    koth_hero_pvp
WHERE
    id_hero = $idHero
AND id_user = $idUser
EOD;
        $this->query($query);
        return ( $this->fetchNext()->is_queued ? true : false );
    }

    // TBD: find best level match first
    public function getOpponentInHeroPvPQueue( $idUser, $idHero )
    {
        $idUser = $this->getQuotedValue( $idUser + 0 );
        $idHero = $this->getQuotedValue( $idHero );

        $query = <<<EOD
SELECT
    hp.id_hero     idHero,
    hp.id_user     idUser
FROM
    koth_hero_pvp hp
    INNER JOIN koth_users_heroes uh1 ON
        uh1.id_user = hp.id_user
    AND uh1.id_hero = hp.id_hero
        INNER JOIN koth_users_heroes uh2 ON
            ABS( uh2.level - uh1.level ) < 4
        AND uh2.id_user = $idUser
        AND uh2.id_hero = $idHero
WHERE
    hp.id_user != $idUser
ORDER BY
    ABS( uh2.level - uh1.level )
EOD;
        $this->query($query);
        $result = $this->fetchNext();

        if ( !$result )
        {
            return null;
        }

        return $result;
    }

    public function getOpponentInRandomPvPQueue()
    {
        $this->query("SELECT id_user FROM koth_random_pvp ORDER BY id LIMIT 1");
        $result = $this->fetchNext();

        if ( !$result )
        {
            return null;
        }

        return $result->id_user;
    }

    public function queueRandomPvP( $idUser )
    {
        $idUser = $this->getQuotedValue( 0 + $idUser );

        $this->query("INSERT INTO koth_random_pvp ( id_user ) VALUES ( $idUser ) ");
    }

    public function queueHeroPvP( $idUser, $idHero )
    {
        $idUser = $this->getQuotedValue( 0 + $idUser );
        $idHero = $this->getQuotedValue( 0 + $idHero );

        $query = <<<EOD
INSERT INTO
    koth_hero_pvp (
        id_user,
        id_hero
    )
SELECT
    $idUser,
    $idHero
EOD;
        $this->query($query);
    }

    public function removeFromPvPQueue( $idUser )
    {
        $idUser = $this->getQuotedValue( 0 + $idUser );

        $this->query("DELETE FROM koth_hero_pvp WHERE id_user = $idUser");
        $this->query("DELETE FROM koth_random_pvp WHERE id_user = $idUser");
    }

    public function startGame( $idUser1, $idHeroMonster1, $idUser2, $idHeroMonster2, $level1 = 0, $level2 = 0 )
    {
        $firstPlayer  = array( 'idUser' => $idUser1,
                               'id'     => $idHeroMonster1,
                               'level'  => $level1 );
        $secondPlayer = array( 'idUser' => $idUser2,
                               'id'     => $idHeroMonster2,
                               'level'  => $level2 );
        // Create game
        $query = <<<EOD
INSERT INTO
    koth_games (
        id_active_player,
        id_step, 
        is_completed, 
        starting_date
    )
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

        // Get game id
        $this->idGame = $this->getQuotedValue( 0 + $this->getInsertId() );

        // Decide randomly who is starting
        $firstPlayer['rank']  = rand(1, 2);
        $secondPlayer['rank'] = 3 - $firstPlayer['rank'];

        foreach ( array( $firstPlayer, $secondPlayer ) as $player )
        {
            $rank          = $this->getQuotedValue( 0 + $player['rank'] );
            $diceNumber    = $this->getQuotedValue( floor( $player['rank'] * KOTH_STARTING_DICE / 2 ) + 0 );
            $idHeroMonster = $this->getQuotedValue( 0 + $player['id'] );

            // Human user
            if ( $player['idUser'] != 0 )
            {
                $idUser    = $this->getQuotedValue( 0 + $player['idUser'] );
                $heroLevel = $this->getQuotedValue( 0 + $player['level'] );
            
                if ( $heroLevel > 0 )
                {
                    $query = <<<EOD
INSERT INTO
    koth_players (
        id_user,
        id_game,
        id_hero,
        level,
        current_mp,
        current_hp,
        current_xp,
        rank,
        dice_number
    )
SELECT
    $idUser,
    {$this->idGame},
    h.id,
    hl.level,
    hl.start_mp,
    hl.start_hp,
    hl.start_xp,
    $rank,
    $diceNumber
FROM
    koth_heroes h
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = h.id
    AND hl.level   = $heroLevel
WHERE
    h.id = $idHeroMonster
EOD;
                }
                else
                {
                    $query = <<<EOD
INSERT INTO
    koth_players (
        id_user,
        id_game,
        id_hero,
        level,
        current_mp,
        current_hp,
        current_xp,
        rank,
        dice_number
    )
SELECT
    $idUser,
    {$this->idGame},
    h.id,
    hl.level,
    hl.start_mp,
    hl.start_hp,
    hl.start_xp,
    $rank,
    $diceNumber
FROM
    koth_heroes h
    INNER JOIN koth_users_heroes uh ON
        uh.id_hero = h.id
    AND uh.id_user = $idUser
        INNER JOIN koth_heroes_levels hl ON
            hl.level   = uh.level
        AND hl.id_hero = uh.id_hero
WHERE
    h.id = $idHeroMonster
EOD;
                }
            }
            // AI
            else
            {
                $query = <<<EOD
INSERT INTO
    koth_players (
        id_user, 
        id_game, 
        id_monster, 
        level, 
        current_mp, 
        current_hp, 
        current_xp, 
        rank,
        dice_number
    )
SELECT
    0,
    {$this->idGame},
    id,
    level,
    start_mp,
    start_hp,
    start_xp,
    $rank,
    $diceNumber
FROM
    koth_monsters
WHERE
    id = $idHeroMonster
EOD;
            }

            $this->query($query);
        }

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
    g.id = {$this->idGame};
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
AND p.id_game = {$this->idGame}
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
AND p.id_game = {$this->idGame}
EOD;
        for ( $i = 0; $i < KOTH_STARTING_DICE; $i++ )
        {
            $this->query($query);
        }

        // Update this object fields
        $this->computeIds();
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
    koth_monsters o
    INNER JOIN koth_players p ON
        p.id_monster = o.id
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
    koth_monsters o
    INNER JOIN koth_players p ON
        p.id_monster = o.id
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

        // Remove wrong victory condition
        if ( $this->getAILevel() >= 2 )
        {
            // Remove magic from possible victory
            if ( $maxes->magic == 3 && $maxes->attack > 3 )
            {
                // Get bad dice
                $tmp = array();
                foreach ( $distribution as $key => $value )
                {
                    if ( $value['type'] == 'magic' )
                    {
                        $tmp[] = $value;
                        unset( $distribution[$key] );
                    }
                }

                // Reverse order to shift values from bigger to lower
                $tmp = array_reverse($tmp);

                // Add bad elements at the start of the array
                foreach ( $tmp as $element )
                {
                    array_unshift($distribution, $element);
                }
            }

            // Remove attack from possible victory
            if ( $maxes->attack == 3 && $maxes->magic > 3 )
            {
                // Get bad dice
                $tmp = array();
                foreach ( $distribution as $key => $value )
                {
                    if ( $value['type'] == 'attack' )
                    {
                        $tmp[] = $value;
                        unset( $distribution[$key] );
                    }
                }

                // Reverse order to shift values from bigger to lower
                $tmp = array_reverse($tmp);

                // Add bad elements at the start of the array
                foreach ( $tmp as $element )
                {
                    array_unshift($distribution, $element);
                }
            }
        }

        // TBD: Health management : high => worst, low => best
        // TBD: Xp management: get a die => good, early game => good, late game => bad, more dice => bad
        // TBD: oriented rush, remove 3rd type if low?
        if ( $this->getAILevel() >= 3 )
        {
            Log_LIB::trace('AI level 3 TBD');
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

        $nowPHP = new DateTime();
        $this->query("SELECT starting_date FROM koth_games WHERE id = {$this->idGame}");
        $startingDate = new DateTime($this->fetchNext()->starting_date);
        $totalHourDiff = floor( ( strtotime($nowPHP->format('Y-m-d H:i:s')) - strtotime($startingDate->format('Y-m-d H:i:s')) ) / 3600 );

        // Winner gets double Xp
        // Player concede => Xp is divided by 2
        // More than 12 hours and loser concede
        //      No extra Xp for winner
        //      No Xp cut for loser
        $winningMultiplier = ( $totalHourDiff >= 12 && $isConcede ) ? '' : ' * 2';
        $losingMultiplier  = ( $totalHourDiff < 12 && $isConcede ? ' / 2 ' : '' );

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
    g.xp_winning_player = g.xp_winning_player + pw.game_xp $winningMultiplier,
    g.xp_losing_player  = g.xp_losing_player  + pl.game_xp $losingMultiplier
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

        // Close game
        $this->query("UPDATE koth_games SET is_completed = 1 WHERE id = {$this->idGame}");
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

    // Player acknowledge game score
    public function closeGame( $idGame )
    {
        $idGame = $this->getQuotedValue( $idGame + 0 );
        $idUser = $this->getQuotedValue( Session_LIB::getUserId() + 0 );

        $this->query("UPDATE koth_players  SET ack_score = 1 WHERE id_user = $idUser AND id_game = $idGame");
    }
}
