<?php

class Koth_PAG_M extends Base_PAG_M
{
    public function getPlayers( $idUser )
    {
        $idUser = $this->getQuotedValue( $idUser );
        
        $query = <<<EOD
SELECT
    p2.current_VP                 VP,
    p2.current_HP                 HP,
    p2.is_active                  is_active,
    p2.is_koth                    is_koth,
    s.name                        status,
    p2.rank                       rank,
    COALESCE( u.username, 'AI' )  user_name,
    h.label                       hero_name
FROM
    koth_players p
    INNER JOIN koth_status s ON
        s.id = p.id_status
    INNER JOIN koth_game_players gp ON
        gp.id_player = p.id
        INNER JOIN koth_games g ON
            g.id        = gp.id_game
        AND g.is_active = 1
            INNER JOIN koth_game_players gp2 ON
                gp2.id_game = g.id
                INNER JOIN koth_players p2 ON
                    p2.id = gp2.id_player
                    INNER JOIN koth_heroes h ON
                        h.id = p2.id_hero
                    LEFT OUTER JOIN users u ON
                        u.id = p2.id_user
WHERE
    p.id_user = $idUser;
EOD;
        $this->query($query);

        $players = array();
        while ( $player = $this->fetchNext() )
        {
            $players[ $player->rank ] = new Koth_LIB_Player( $player );
        }

        return $players;
    }
}
