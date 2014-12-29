<?php

class Koth_LIB_Scores_View extends Base_LIB_View
{
    public function render()
    {
        $data = $this->getData();

        $toDisplay  = "<h2>Game score</h2>\n";
        
        $message = 'You lose, it\'s ok, you\'ll do better next time';
        $experience = $data['experience']->xp_loser;
        if (Session_LIB::getUserId() == $data['experience']->id_winner )
        {
            $message = 'You won, congratulations!';
            $experience = $data['experience']->xp_winner;
        }

        $toDisplay .= "<h4>$message</h4>" . ALL_EOL;
        $toDisplay .= "You won $experience Xp" . ALL_EOL;
        
        echo $toDisplay;
    }
}
