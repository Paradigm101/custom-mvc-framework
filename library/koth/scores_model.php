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
    g.xp_losing_user    xp_loser,
    pw.current_hp       hp_winner,
    pl.current_hp       hp_loser,
    pw.current_vp       vp_winner,
    pl.current_vp       vp_loser
FROM
    koth_games g
    INNER JOIN koth_players p ON
        p.id_game = g.id
    AND p.id_user = {$this->idUser}
    INNER JOIN koth_players pw ON
        pw.id_game = g.id
    AND pw.id_user = g.id_winning_user
    INNER JOIN koth_players pl ON
        pl.id_game = g.id
    AND pl.id_user = g.id_losing_user
WHERE
    g.is_completed = 0
EOD;
        $this->query($query);
        $result = $this->fetchNext();

        // Add data
        $result->isWinner = ( $result->id_winner == $this->idUser );

        return $result;
    }
}
