<?php

class Koth_PAG_V extends Base_PAG_V
{
    protected function getExtraTemplates()
    {
        $data = $this->getData();

        if ( Session_LIB::isUserLoggedIn() )
        {
            if ( $data['game']->isGameActive() )
            {
                // Game is finished, display scores
                if ( $data['game']->isGameFinished() )
                {
                    $template = 'scores';
                }
                // Game is running
                else
                {
                    $template = 'running';
                }
            }
            // No game running, dashboard
            else
            {
                $template = 'dashboard';
            }
        }
        // User is not logged in: can't play
        // TBD: play as a guest?
        else
        {
            $template = 'presentation';
        }

        return array( $template );
    }
}
