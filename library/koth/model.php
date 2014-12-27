<?php

class Koth_LIB_Model extends Base_LIB_Model
{
    public function getPlayerResults( $idUser )
    {
        $idUser = $this->getQuotedValue(0+$idUser);
        
        $query = <<<EOD
SELECT
    d.name  die_name
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
                AND p.id_user = $idUser;
EOD;

        $this->query($query);
        $dice = $this->fetchAll();

        // No die = no results
        if ( !$dice )
        {
            return array( 0, 0, 0, 0 );
        }

        $attack     = 0;
        $heal       = 0;
        $experience = 0;
        $victory    = array();
        foreach ( $dice as $die )
        {
            switch ( strtolower( $die->die_name ) )
            {
                case 'attack':
                    $attack++;
                    break;

                case 'heart':
                    $heal++;
                    break;

                case 'experience':
                    $experience++;
                    break;

                case 'victory_1':
                case 'victory_2':
                case 'victory_3':
                    $tmp = explode('_', $die->die_name);
                    $victory[] = $tmp[1];
            }
        }

        $vp      = 0;
        $victory = array_count_values($victory);
        foreach ( $victory as $points => $occurence )
        {
            $vp += ( $occurence < 3 ? 0 : $points + ( $occurence - 3 ) );
        }

        return array( $attack, $heal, $experience, $vp );
    }

    // Return user's player's status (if any active game)
    public function getPlayerStatus( $idUser )
    {
        $idUser = $this->getQuotedValue($idUser);

        $this->query("SELECT s.name FROM koth_players p INNER JOIN koth_status s ON s.id = p.id_status WHERE id_user = $idUser");
        $result = $this->fetchNext();

        return $result ? $result->name : null;
    }
    
    // Return player's dice
    public function getPlayerDice( $idUser )
    {
        $idUser = $this->getQuotedValue( $idUser );
        
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
                AND p.id_user = $idUser ;
EOD;

        $this->query($query);
        return $this->fetchAll();
    }

    // Go to next step for player status
    public function setNextStep( $idUser )
    {
        // Get status
        $status = $this->getPlayerStatus($idUser);
        
        switch ( $status )
        {
            case 'before_roll_1':
                $newStatus = 'after_roll_1';
                break;
            case 'after_roll_1':
                $newStatus = 'after_roll_2';
                break;
            case 'after_roll_2':
                $newStatus = 'after_roll_3';
                break;
            case 'after_roll_3':
                $newStatus = 'after_ai';
                break;
            case 'after_ai':
                $newStatus = 'before_roll_1';
                break;
        }

        $idUser    = $this->getQuotedValue( 0 + $idUser );
        $newStatus = $this->getQuotedValue( $newStatus );

        // Next step
        $this->query("UPDATE koth_players p INNER JOIN koth_status s ON s.name = $newStatus SET p.id_status = s.id WHERE id_user = $idUser ;");
    }

    public function playAI( $idUser )
    {
        $idUser = $this->getQuotedValue( 0 + $idUser );

        // First draw
        $dice = Koth_LIB::getRandomDieNames( 6 );

        // For the moment: keep everything and no other draw
        // TBD: to improve!

        // Set dice for AI (for board to display)
    }
}
