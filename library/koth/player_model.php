<?php

class Koth_LIB_Player_Model extends Base_LIB_Model
{
    public function getHeroDie( $idPlayer )
    {
        $idPlayer = $this->getQuotedValue( 0 + $idPlayer );

        $query = <<<EOD
SELECT
    hl.max_attack       max_attack,
    hl.max_health       max_health,
    hl.max_experience   max_experience,
    hl.max_magic        max_magic
FROM
    koth_players p
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = p.id_hero
    AND hl.level   = p.level
WHERE
    p.id = $idPlayer
UNION
SELECT
    o.max_attack       max_attack,
    o.max_health       max_health,
    o.max_experience   max_experience,
    o.max_magic        max_magic
FROM
    koth_players p
    INNER JOIN koth_monsters o ON
        o.id = p.id_monster
WHERE
    p.id = $idPlayer
EOD;
        $this->query($query);
        return $this->fetchNext();
    }

    public function getData( $idPlayer )
    {
        $idPlayer = $this->getQuotedValue( 0 + $idPlayer );

        $query = <<<EOD
SELECT
    p.current_hp        currentHP,
    p.current_mp        currentMP,
    p.current_xp        currentXP,
    p.dice_number       diceNumber,
    u.username          userName,
    ux.level            userLevel,
    h.label             heroName,
    p.level             heroLevel,
    hl.start_hp         maxHP,
    COALESCE( g.id, 0 ) isActive
FROM
    koth_players p
    LEFT OUTER JOIN koth_games g ON
        g.id               = p.id_game
    AND g.id_active_player = p.id
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
    p.id = $idPlayer
UNION
SELECT
    p.current_hp        currentHP,
    p.current_mp        currentMP,
    p.current_xp        currentXP,
    p.dice_number       diceNumber,
    'AI'                userName,
    o.ai_level          userLevel,
    o.label             heroName,
    o.level             heroLevel,
    o.start_hp          maxHP,
    COALESCE( g.id, 0 ) isActive
FROM
    koth_players p
    INNER JOIN koth_monsters o ON
        o.id = p.id_monster
    LEFT OUTER JOIN koth_games g ON
        g.id = p.id_game
    AND g.id_active_player = p.id
WHERE
    p.id = $idPlayer
EOD;
        $this->query($query);
        $result = $this->fetchNext();

        return $result;
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

    // Update player's data in DB
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

        // Update active player's health/magic/xp
        // AI user
        $query = <<<EOD
UPDATE
    koth_players p
    INNER JOIN koth_monsters m ON
        m.id = p.id_monster
SET
    p.current_xp = p.current_xp + $experience,
    p.current_mp = p.current_mp + $magic,
    p.current_hp = LEAST( p.current_hp + $health, m.start_hp ),
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
        $this->query("SELECT current_xp, dice_number FROM koth_players WHERE id = $idActivePlayer");
        $result = $this->fetchNext();

        $newDiceNumber = KOTH_STARTING_DICE + floor( $result->current_xp / Koth_LIB_Game::getXpDicePrice() );
        $diceToAdd     = $newDiceNumber - $result->dice_number ;

        // If there are dice to add
        if ( $diceToAdd > 0 )
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
            // Add dice
            for( $i = 0; $i < $diceToAdd; $i++ )
            {
                $this->query($query);
            }

            // Update dice number
            $this->query("UPDATE koth_players SET dice_number = $newDiceNumber WHERE id = $idActivePlayer");
        }
    }
}
