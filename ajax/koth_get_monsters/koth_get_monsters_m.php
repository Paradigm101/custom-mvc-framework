<?php

class Koth_Get_Monsters_AJA_M extends Base_AJA_M
{
    public function getMonsters( $level = 1, $ai = 0 )
    {
        $level = $this->getQuotedValue($level);
        $ai    = $this->getQuotedValue($ai);

        $query = <<<EOD
SELECT
    id,
    name,
    label
FROM
    koth_monsters
WHERE
    level    = $level
AND ai_level = $ai
ORDER BY
    name
EOD;
        $this->query($query);
        return $this->fetchAll('array');
    }
}
