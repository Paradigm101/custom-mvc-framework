<?php

class Koth_LIB_Board_Model extends Base_LIB_Model
{
    private $idUser;
    
    public function __construct( $idUser )
    {
        parent::__construct();
        
        $this->idUser = $this->getQuotedValue(0+$idUser);
    }

    public function getDice()
    {
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
            g.id               = p.id_game
        AND g.is_completed     = 0
        AND g.id_active_player = p.id
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
}
