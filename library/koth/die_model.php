<?php

class Koth_LIB_Die_Model extends Base_LIB_Model
{
    public function getDice( $isActivePlayer = true )
    {
        $idUser = $this->getQuotedValue( 0 + Session_LIB::getUserId() );
        $activeCdtn = ' g.id_active_player ' . ( $isActivePlayer ? '' : '!' ) . '= p.id ';

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
            AND p2.id_user = $idUser
EOD;
        $this->query($query);
        return $this->fetchAll();
    }
}
