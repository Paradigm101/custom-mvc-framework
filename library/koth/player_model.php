<?php

class Koth_LIB_Player_Model extends Base_LIB_Model
{
    public function getHeroDie( $isOtherUser = false )
    {
        $idUser   = $this->getQuotedValue( 0 + Session_LIB::getUserId() );
        $otherCdt = ' p.id_user ' . ( $isOtherUser ? '!' : '' ) . "= $idUser ";

        $query = <<<EOD
SELECT
    hl.max_attack       max_attack,
    hl.max_health       max_health,
    hl.max_experience   max_experience,
    hl.max_magic      max_magic
FROM
    koth_players p
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = p.id_hero
    AND hl.level   = p.level
    INNER JOIN koth_games g ON
        g.id           = p.id_game
    AND g.is_completed = 0
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
        AND p2.id_user = $idUser
WHERE
    p.id_user != 0
AND $otherCdt
UNION
SELECT
    o.max_attack       max_attack,
    o.max_health       max_health,
    o.max_experience   max_experience,
    o.max_magic      max_magic
FROM
    koth_players p
    INNER JOIN koth_opponents o ON
        o.id = p.id_opponent
    INNER JOIN koth_games g ON
        g.id           = p.id_game
    AND g.is_completed = 0
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
        AND p2.id_user = $idUser
WHERE
    p.id_user = 0
AND $otherCdt
EOD;
        $this->query($query);
        return $this->fetchNext();
    }

    public function getPlayerData( $isOtherUser = false )
    {
        $idUser = $this->getQuotedValue( 0 + Session_LIB::getUserId() );
        $otherCdt = ' p.id_user ' . ( $isOtherUser ? '!' : '' ) . "= $idUser ";

        $query = <<<EOD
SELECT
    p.id                id_player,
    p.current_hp        current_hp,
    p.current_mp        current_mp,
    p.current_xp        current_xp,
    u.username          user_name,
    ux.level            user_level,
    h.label             hero_label,
    p.level        level,
    hl.start_hp         max_hp,
    g.id_active_player  id_active_player
FROM
    koth_players p
    INNER JOIN koth_games g ON
        g.id           = p.id_game
    AND g.is_completed = 0
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
        AND p2.id_user = $idUser
    INNER JOIN users u ON
        u.id = p.id_user
    INNER JOIN koth_user_xp ux ON
        ux.id_user = p.id_user
    INNER JOIN koth_heroes h ON
        h.id = p.id_hero
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = p.id_hero
    AND hl.level   = p.level
WHERE
    $otherCdt
AND p.id_user != 0
UNION
SELECT
    p.id                id_player,
    p.current_hp        current_hp,
    p.current_mp        current_mp,
    p.current_xp        current_xp,
    'AI'                user_name,
    o.ai_level          user_level,
    o.label             hero_label,
    o.level             level,
    o.start_hp          max_hp,
    g.id_active_player  id_active_player
FROM
    koth_players p
    INNER JOIN koth_games g ON
        g.id           = p.id_game
    AND g.is_completed = 0
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
        AND p2.id_user = $idUser
    INNER JOIN koth_opponents o ON
        o.id = p.id_opponent
WHERE
    $otherCdt
AND p.id_user = 0
EOD;
        $this->query($query);
            
        $result = $this->fetchNext();

        $player = new stdClass();
        
        $player->userName   = $result->user_name;
        $player->userLevel  = $result->user_level;
        $player->heroName   = $result->hero_label;
        $player->heroLevel  = $result->level;
        $player->currentHP  = $result->current_hp;
        $player->maxHP      = $result->max_hp;
        $player->currentMP  = $result->current_mp;
        $player->currentXP  = $result->current_xp;
        $player->isActive   = ( $result->id_active_player == $result->id_player ? true : false );
        $player->diceNumber = Koth_LIB_Die::getDiceNumber( $isOtherUser );

        return $player;
    }

    // Get player's results
    public function getResults( $idPlayer )
    {
        $idPlayer = $this->getQuotedValue( 0 + $idPlayer );
        
        $query = <<<EOD
SELECT
    pd.value,
    dt.name
FROM
    koth_players_dice pd
    INNER JOIN koth_die_types dt ON
        dt.id = pd.id_die_type
WHERE
    pd.id_player = $idPlayer
EOD;
        $this->query($query);
        return $this->fetchAll();
    }

    // health add to active player
    // experience add to active player
    // magic points add to active player
    // attack remove health from non-active player
    // update game_xp for active player
    public function storeResults( $results, $idActivePlayer )
    {
        $experience = $this->getQuotedValue( 0 + ( array_key_exists('experience', $results) ? $results['experience'] : 0 ) );
        $magic      = $this->getQuotedValue( 0 + ( array_key_exists('magic',      $results) ? $results['magic']      : 0 ) );
        $health     = $this->getQuotedValue( 0 + ( array_key_exists('health',     $results) ? $results['health']     : 0 ) );
        $attack     = $this->getQuotedValue( 0 + ( array_key_exists('attack',     $results) ? $results['attack']     : 0 ) );
        $gameXp     = $this->getQuotedValue( 0 + $experience + $magic + $health + $attack );
        
        $idActivePlayer   = $this->getQuotedValue( 0 + $idActivePlayer );

        // Update active player's health/magic/xp
        // Human user
        $query = <<<EOD
UPDATE
    koth_players p
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = p.id_hero
    AND hl.level   = p.level
SET
    p.current_xp = p.current_xp + $experience,
    p.current_mp = p.current_mp + $magic,
    p.current_hp = LEAST( p.current_hp + $health, hl.start_hp ),
    p.game_xp    = p.game_xp + $gameXp
WHERE
    p.id = $idActivePlayer
EOD;
        $this->query($query);

        $query = <<<EOD
UPDATE
    koth_players p
    INNER JOIN koth_opponents o ON
        o.id = p.id_opponent
SET
    p.current_xp = p.current_xp + $experience,
    p.current_mp = p.current_mp + $magic,
    p.current_hp = LEAST( p.current_hp + $health, o.start_hp ),
    p.game_xp    = p.game_xp + $gameXp
WHERE
    p.id = $idActivePlayer
EOD;
        $this->query($query);

        // Update inactive player's health
        $this->query("  UPDATE
                            koth_players p
                            INNER JOIN koth_players p2 ON
                                p2.id_game = p.id_game
                            AND p2.id      = $idActivePlayer
                            AND p2.id     != p.id
                        SET 
                            p.current_hp = ( p.current_hp - $attack )");

        // Get extra dice from Xp
        $this->query("SELECT current_xp FROM koth_players WHERE id = $idActivePlayer");
        $current_xp = $this->fetchNext()->current_xp;

        // Get dice pool number
        $query = <<<EOD
SELECT
    COUNT(1)    num
FROM
    koth_players_dice pd
WHERE
    pd.id_player = $idActivePlayer
EOD;
        $this->query($query);
        $dicePoolNumber = $this->fetchNext()->num;

        if ( $num = ( KOTH_STARTING_DICE + floor( $current_xp / Koth_LIB_Game::getXpDicePrice() ) - $dicePoolNumber ) )
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
    INNER JOIN koth_die_types dt ON
        dt.name = 'unknown'
WHERE
    p.id = $idActivePlayer
EOD;
            for( $i = 0; $i < $num; $i++ )
            {
                $this->query($query);
            }
        }
    }
}
