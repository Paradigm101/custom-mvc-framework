<?php

class Koth_LIB_Board_Model extends Base_LIB_Model
{
    private $idUser;

    public function __construct( $idUser )
    {
        parent::__construct();
        
        $this->idUser = $this->getQuotedValue(0+$idUser);
    }

    public function getDice( $nonActive = false )
    {
        $activeCdtn = ' g.id_active_player ' . ( $nonActive ? '!' : '' ) . '= p.id';
        
        $query = <<<EOD
SELECT
    pd.id       id,
    dt.name     name,
    dt.label    label,
    pd.value    value,
    pd.keep     keep
FROM
    koth_players_dice pd
    INNER JOIN koth_die_types dt ON
        dt.id = pd.id_die_type
    INNER JOIN koth_players p ON
        p.id = pd.id_player
        INNER JOIN koth_games g ON
            g.id           = p.id_game
        AND g.is_completed = 0
        AND $activeCdtn
            INNER JOIN koth_players p2 ON
                p2.id_game = g.id
            AND p2.id_user = {$this->idUser}
EOD;
        $this->query($query);
        return $this->fetchAll();
    }
    
    public function canUserRoll()
    {
        $query = <<<EOD
SELECT
    COUNT(1) is_active
FROM
    koth_players p
    INNER JOIN koth_games g ON
        g.id               = p.id_game
    AND g.is_completed     = 0
    AND g.id_active_player = p.id
        INNER JOIN koth_steps s ON
            s.id    = g.id_step
        AND s.name != 'after_roll_3'
WHERE
    p.id_user = {$this->idUser} ;
EOD;
        $this->query($query);
        
        return ( $this->fetchNext()->is_active ? true : false );
    }
    
    public function isFirstPlayerFirstTurn()
    {
        $query = <<<EOD
SELECT
    COUNT(1)    is_first_player_first_turn
FROM
    koth_players p
    INNER JOIN koth_games g ON
        g.id               = p.id_game
    AND g.is_completed     = 0
    AND g.id_active_player = p.id
    AND g.turn_number      = 1
        INNER JOIN koth_players p2 ON
            p2.id_game = g.id
        AND p2.id_user = {$this->idUser}
WHERE
    p.id_user = {$this->idUser}
AND p.rank    = 1
EOD;
        $this->query($query);
        return ( $this->fetchNext()->is_first_player_first_turn ? true : false );
    }

    public function getStep()
    {
        $query = <<<EOD
SELECT
    s.name  name
FROM
    koth_steps s
    INNER JOIN koth_games g ON
        g.id_step      = s.id
    AND g.is_completed = 0
        INNER JOIN koth_players p ON
            p.id_game = g.id
        AND p.id_user = {$this->idUser};
EOD;
        $this->query($query);
        return $this->fetchNext()->name;
    }
    
    public function isActive()
    {
        $query = <<<EOD
SELECT
    COUNT(1)    is_active
FROM
    koth_players p
    INNER JOIN koth_games g ON
        g.id               = p.id_game
    AND g.is_completed     = 0
    AND g.id_active_player = p.id
WHERE
    p.id_user = {$this->idUser}
EOD;
        $this->query($query);
        return ( $this->fetchNext()->is_active ? true : false );
    }
}
