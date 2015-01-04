<?php

class Koth_LIB_Die_Model extends Base_LIB_Model
{
    public function getDice( $idPlayer )
    {
        $idPlayer = $this->getQuotedValue( 0 + $idPlayer );

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
WHERE
    pd.id_player = $idPlayer
ORDER BY
    pd.id
EOD;
        $this->query($query);
        return $this->fetchAll();
    }
}
