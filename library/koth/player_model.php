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
    hl.max_victory      max_victory
FROM
    koth_players p
    INNER JOIN koth_heroes_levels hl ON
        hl.id_hero = p.id_hero
    AND hl.level   = p.hero_level
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
    o.max_victory      max_victory
FROM
    koth_players p
    INNER JOIN koth_opponents o ON
        o.id = p.id_hero
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
    p.current_vp        current_vp,
    p.current_xp        current_xp,
    u.username          user_name,
    ux.level            user_level,
    h.label             hero_label,
    p.hero_level        hero_level,
    hl.start_hp         max_hp,
    g.id_active_player  id_active_player,
    COUNT(pd.id_player) dice_number
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
    AND hl.level   = p.hero_level
    INNER JOIN koth_players_dice pd ON
        pd.id_player = p.id
WHERE
    $otherCdt
AND p.id_user != 0
GROUP BY
    pd.id_player
UNION
SELECT
    p.id                id_player,
    p.current_hp        current_hp,
    p.current_vp        current_vp,
    p.current_xp        current_xp,
    'AI'                user_name,
    o.ai_level          user_level,
    o.label             hero_label,
    o.level             hero_level,
    o.start_hp          max_hp,
    g.id_active_player  id_active_player,
    COUNT(pd.id_player) dice_number
FROM
    koth_players p
    INNER JOIN koth_games g ON
        g.id           = p.id_game
    AND g.is_completed = 0
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
        AND p2.id_user = $idUser
    INNER JOIN koth_opponents o ON
        o.id = p.id_hero
    INNER JOIN koth_players_dice pd ON
        pd.id_player = p.id
WHERE
    $otherCdt
AND p.id_user = 0
GROUP BY
    pd.id_player
EOD;
        $this->query($query);
            
        $result = $this->fetchNext();

        $player = new stdClass();
        
        $player->userName   = $result->user_name;
        $player->userLevel  = $result->user_level;
        $player->heroName   = $result->hero_label;
        $player->heroLevel  = $result->hero_level;
        $player->currentHP  = $result->current_hp;
        $player->maxHP      = $result->max_hp;
        $player->currentVP  = $result->current_vp;
        $player->currentXP  = $result->current_xp;
        $player->isActive   = ( $result->id_active_player == $result->id_player ? true : false );
        $player->diceNumber = $result->dice_number;

        return $player;
    }
}
