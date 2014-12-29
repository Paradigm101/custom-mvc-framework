<?php

class Koth_LIB_Player_Model extends Base_LIB_Model
{
    private $idUser;
    private $isOther;

    public function __construct( $idUser, $isOther = false )
    {
        parent::__construct();

        $this->idUser  = $this->getQuotedValue( 0 + $idUser );
        $this->isOther = $this->getQuotedValue( 0 + $isOther );
    }

    public function getHeroDie()
    {
        $otherCdt = ' AND p.id_user ' . ( $this->isOther ? '!' : '' ) . '= ' . $this->idUser . ' ';

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
    $otherCdt
        INNER JOIN koth_games g ON
            g.id           = p.id_game
        AND g.is_completed = 0
            INNER JOIN koth_players p2 ON
                p2.id_game = g.id
            AND p2.id_user = {$this->idUser}
EOD;
        $this->query($query);
        return $this->fetchNext();
    }

    public function getPlayerData()
    {
        $playerCondition = ' AND p2.id_user ' . ( $this->isOther ? '!' : '' ) . '= ' . $this->idUser;
        
        $query = <<<EOD
SELECT
    p2.id                           id_player,
    p2.current_hp                   current_hp,
    p2.current_vp                   current_vp,
    p2.current_xp                   current_xp,
    COALESCE( u.username, 'AI' )    user_name,
    COALESCE( ux.level, 1 )         user_level,
    h.label                         hero_label,
    p2.hero_level                   hero_level,
    hl.start_hp                     max_hp,
    g.id_active_player              id_active_player,
    s.name                          step,
    COUNT(pd.id_player)             dice_number
FROM
    koth_players p
    INNER JOIN koth_games g ON
        g.id           = p.id_game
    AND g.is_completed = 0
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
            $playerCondition
            LEFT OUTER JOIN users u ON
                u.id = p2.id_user
            LEFT OUTER JOIN koth_user_xp ux ON
                ux.id_user = p2.id_user
            INNER JOIN koth_heroes h ON
                h.id = p2.id_hero
            INNER JOIN koth_heroes_levels hl ON
                hl.id_hero = p2.id_hero
            AND hl.level   = p2.hero_level
        INNER JOIN koth_steps s ON
            s.id = g.id_step
        INNER JOIN koth_players_dice pd ON
            pd.id_player = p2.id
WHERE
    p.id_user = {$this->idUser}
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
        $player->step       = $result->step;
        $player->diceNumber = $result->dice_number;

        return $player;
    }
}
