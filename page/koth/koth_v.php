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
                $template = 'running';
            }
            else
            {
                $template = 'dashboard';
            }
        }
        else
        {
            $template = 'presentation';
        }
        
        return array( $template );
    }
}
