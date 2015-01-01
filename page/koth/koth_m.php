<?php

class Koth_PAG_M extends Base_PAG_M
{
    // User data (dashboard)
    public function getUserData( $idUser )
    {
        $query = <<<EOD
SELECT
    u.username                      user_name,
    COALESCE( ux.level, 1 )         user_level,
    COALESCE( ux.experience, 0 )    user_experience,
    uxl.threshold                   next_level_xp
FROM
    users u
    LEFT OUTER JOIN koth_user_xp ux ON
        ux.id_user = u.id
        LEFT OUTER JOIN koth_user_xp_level uxl ON
            uxl.level = COALESCE( ux.level, 1 )
WHERE
    u.id = $idUser
EOD;
        $this->query($query);
        return $this->fetchNext();
    }

    // Hero data for user (dashboard)
    public function getHeroesData( $idUser )
    {
        $query = <<<EOD
SELECT
    h.label                         hero_label,
    h.name                          hero_name,
    COALESCE( uh.level, 1 )         hero_level,
    COALESCE( uh.experience, 0 )    hero_experience,
    hxl.threshold                   next_level_xp
FROM
    koth_heroes h
    LEFT OUTER JOIN koth_users_heroes uh ON
        uh.id_hero = h.id
    AND uh.id_user = $idUser
        LEFT OUTER JOIN koth_hero_xp_level hxl ON
            hxl.level = COALESCE( uh.level, 1 )
EOD;
        $this->query($query);
        return $this->fetchAll();
    }
}
