<?php

class Koth_PAG_M extends Base_PAG_M
{
    public function getPlayers( $idUser )
    {
        $query = <<<EOD
SELECT
    p2.current_VP                 VP,
    p2.current_HP                 HP,
    p2.is_active                  is_active,
    p2.is_koth                    is_koth,
    p2.roll_done                  roll_done,
    p2.rank                       rank,
    COALESCE( u.username, 'AI' )  user_name,
    h.label                       hero_name
FROM
    koth_players p
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
        while ( $playerDB = $this->fetchNext() )
        {
            $players[ $playerDB->rank ] = new Koth_LIB_Player( $playerDB->VP,
                                                               $playerDB->HP,
                                                               $playerDB->is_active,
                                                               $playerDB->is_koth,
                                                               $playerDB->roll_done,
                                                               $playerDB->user_name,
                                                               $playerDB->hero_name );
        }

        return $players;
    }
    
    public function getBoard( $idUser, $diceNumber = 6 )
    {
        $query = <<<EOD
SELECT
    gd.id       id,
    d.name      name,
    d.label     label,
    d.picture   picture
FROM
    koth_dice d
    INNER JOIN koth_game_dice gd ON
        gd.id_dice = d.id
        INNER JOIN koth_games g ON
            g.id        = gd.id_game
        AND g.is_active = 1
            INNER JOIN koth_game_players gp ON
                gp.id_game = g.id
                INNER JOIN koth_players p ON
                    p.id      = gp.id_player
                AND p.id_user = $idUser
WHERE
    p.id_user = $idUser;
EOD;

        // Get dice in DB
        $this->query($query);
        $dice = $this->fetchAll();

        // Store in a board sent
        $board = new Koth_LIB_Board();
        foreach ( $dice as $die )
        {
            $board->addDie( new Koth_LIB_Die( $die ) );
        }

        return $board;
    }
}
