<?php

class Koth_LIB_News_Model extends Base_LIB_Model
{
    private $idUser;
    
    public function __construct( $idUser )
    {
        parent::__construct();
        
        $this->idUser = $this->getQuotedValue(0+$idUser);
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
