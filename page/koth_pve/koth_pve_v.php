<?php

class Koth_PvE_PAG_V extends Base_PAG_V
{
    protected function getTitle()
    {
        return 'Koth - PvE';
    }

    protected function getExtraTemplates()
    {
        switch ( Koth_LIB_Game::getStatus() )
        {
            // Game running
            case KOTH_STATUS_RUNNING:
                return array('../koth/running');

            // Game score
            case KOTH_STATUS_SCORE:
                return array('../koth/score');

            // No game
            case KOTH_STATUS_NOT_STARTED:
                return array( '../koth/no_running' );
        }
    }
}
