<?php

class Koth_PAG_V extends Base_PAG_V
{
    protected function getExtraTemplates()
    {
        // Add template according to game status
        switch ( Koth_LIB_Game::getStatus() )
        {
            // No started game: Dashboard
            case KOTH_STATUS_NOT_STARTED:
                return array('dashboard');
            
            // Game running
            case KOTH_STATUS_RUNNING:
                return array('running');
            
            // Game just ended: Scores
            case KOTH_STATUS_FINISHED:
                return array('scores');
            
            // No user, nothing to do
            case KOTH_STATUS_NO_USER:
            default:
                return array('presentation');
        }
    }
}
