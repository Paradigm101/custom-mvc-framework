<?php

class Koth_PvE_PAG_V extends Base_PAG_V
{
    protected function getTitle()
    {
        return 'Koth - PvE';
    }

    protected function getExtraTemplates()
    {
        if ( !Koth_LIB_Game::isPlayingPvE( Session_LIB::getUserId() ) )
        {
            return array( 'no_running' );
        }
        
        // Add template according to game status
        switch ( Koth_LIB_Game::getStatus() )
        {
            // Game running
            case KOTH_STATUS_RUNNING:
                return array('running');

            // Game score
            case KOTH_STATUS_SCORE:
                return array('score');
        }
    }
}
