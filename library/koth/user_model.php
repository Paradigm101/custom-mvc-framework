<?php

class Koth_LIB_User_Model extends Base_LIB_Model
{
    public function getData( $idUser = null )
    {
        if ( !$idUser )
        {
            $idUser = Session_LIB::getUserId();
        }
        $idUser = $this->getQuotedValue( 0 + $idUser );
        
        $query = <<<EOD
SELECT
    u.username      user_name,
    ux.level        user_level,
    ux.experience   user_experience,
    uxl.threshold   next_level_xp
FROM
    users u
    INNER JOIN koth_user_xp ux ON
        ux.id_user = u.id
        INNER JOIN koth_user_xp_level uxl ON
            uxl.level = ux.level
WHERE
    u.id = {$idUser}
EOD;
        $this->query($query);
        return $this->fetchNext();
    }

    public function getHeroes( $idUser = null )
    {
        if ( !$idUser )
        {
            $idUser = Session_LIB::getUserId();
        }
        $idUser = $this->getQuotedValue( 0 + $idUser );
        
        $query = <<<EOD
SELECT
    h.label         hero_label,
    h.name          hero_name,
    uh.level        level,
    uh.experience   hero_experience,
    hxl.threshold   next_level_xp
FROM
    koth_heroes h
    INNER JOIN koth_users_heroes uh ON
        uh.id_hero = h.id
    AND uh.id_user = {$idUser}
        INNER JOIN koth_hero_xp_level hxl ON
            hxl.level = uh.level
EOD;
        $this->query($query);
        return $this->fetchAll();
    }
}
