<?php

class Koth_LIB_Scores_Model extends Base_LIB_Model
{
    private $idUser;
    
    public function __construct( $iduser ) {
        parent::__construct();
        
        $this->idUser = $this->getQuotedValue($iduser);
    }

    public function getExperience()
    {
        $query = <<<EOD
SELECT
    g.id_winning_user   id_winner,
    g.xp_winning_user   xp_winner,
    g.xp_losing_user    xp_loser
FROM
    koth_games g
    INNER JOIN koth_players p ON
        p.id_game = g.id
    AND p.id_user = {$this->idUser}
WHERE
    g.is_completed = 0
EOD;
        $this->query($query);
        return $this->fetchNext();
    }
}
