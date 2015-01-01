<?php

class Koth_LIB_Results_Model extends Base_LIB_Model
{
    public function getPlayerResults( $idUser, $isActive = true )
    {
        $idUser = $this->getQuotedValue( 0 + $idUser );
        
        $activeCondition = ' AND g.id_active_player ' . ( $isActive ? '' : '!' ) . '= p.id ';
        
        $query = <<<EOD
SELECT
    pd.value,
    dt.name
FROM
    koth_players_dice pd
    INNER JOIN koth_players p ON
        p.id       = pd.id_player
        INNER JOIN koth_games g ON
            g.id               = p.id_game
        AND g.is_completed     = 0
        $activeCondition
            INNER JOIN koth_players p2 ON
                p2.id_game = g.id
            AND p2.id_user = $idUser
    INNER JOIN koth_die_types dt ON
        dt.id = pd.id_die_type
EOD;

        $this->query($query);
        return $this->fetchAll();
    }
}
