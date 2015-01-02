<?php

class Koth_Get_Opponents_AJA_M extends Base_AJA_M
{
    public function getOpponents( $level = 1, $ai = 0 )
    {
        $level = $this->getQuotedValue($level);
        $ai    = $this->getQuotedValue($ai);

        $query = <<<EOD
SELECT
    name,
    label
FROM
    koth_opponents
WHERE
    level    = $level
AND ai_level = $ai
EOD;
        $this->query($query);
        return $this->fetchAll('array');
    }
}
