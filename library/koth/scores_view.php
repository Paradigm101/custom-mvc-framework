<?php

class Koth_LIB_Scores_View extends Base_LIB_View
{
    public function render()
    {
        $data = $this->getData();
        $data = $data['experience'];

        if ( $data->isWinner )
        {
            $message    = 'You won.';
            $experience = $data->xp_winner;
            
            if ( $data->hp_loser <= 0 )
            {
                $message .= ' Your opponent has been beaten to death.';
            }
            if ( $data->vp_winner >= KOTH_VICTORY_WIN )
            {
                $message .= ' You reached victory the threshold.';
            }
        }
        else
        {
            $message    = "You lose.";
            $experience = $data->xp_loser;
            
            if ( $data->hp_loser <= 0 )
            {
                $message .= ' You have been beaten to death.';
            }
            if ( $data->vp_winner >= KOTH_VICTORY_WIN )
            {
                $message .= ' Your opponent reached the victory threshold.';
            }
        }
        
        $toDisplay  = "<h2>Game score</h2>\n";
        $toDisplay .= "<h4>$message</h4>" . ALL_EOL;
        $toDisplay .= "You won $experience Xp" . ALL_EOL;

        echo $toDisplay;
    }
}
