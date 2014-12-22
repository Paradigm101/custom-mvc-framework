<?php

class Koth_PAG_V extends Base_PAG_V {

    // Add templates
    protected function getExtraTemplates() {

        $data = $this->getData();
        $playerNumber = count( $data['players'] );

        $extraTemplate = array( $playerNumber . '_player' );
        
        if ( $playerNumber )
        {
            $extraTemplate[] = 'running';
        }
        
        return $extraTemplate;
    }
}
