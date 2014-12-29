<?php

class Koth_LIB_Board_View extends Base_LIB_View
{
    // TBD: manage more than 10 dice
    // TBD: manage image on hover not centered
    public function render()
    {
        // Init
        $data = $this->getData();
        $toDisplay = '';

        // Get dice
        $dice = array();
        foreach ( $data['dice'] as $dataDie )
        {
            // Create die
            $dice[] = new Koth_LIB_Die( $dataDie, $data['rollable'] );
        }

        // Start row
        $toDisplay .= '<div class="row" style="height: 85px;">';

        // Margin
        $toDisplay .= '<div class="col-xs-1"></div>';
        
        // Display dice
        foreach ( $dice as $die )
        {
            $toDisplay .= '<div class="col-xs-1">';
            $toDisplay .= $die->display();
            $toDisplay .= '</div>';
        }

        // Margin
        $toDisplay .= '<div class="col-xs-1"></div>';
        
        // End row
        $toDisplay .= '</div>';

        echo $toDisplay;
    }
}
