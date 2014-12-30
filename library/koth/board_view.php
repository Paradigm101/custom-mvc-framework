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
        
        // TBD: manage more than 12 dice
        $marginLeft = floor( ( 12 - count( $dice ) ) / 2 );
        $marginRight = ceil( ( 12 - count( $dice ) ) / 2 );

        // Start row
        $toDisplay .= '<div class="row" style="height: 85px;">';

        // Margin
        if ( $marginLeft )
        {
            $toDisplay .= '<div class="col-xs-' . $marginLeft . '"></div>';
        }
        
        // Display dice
        foreach ( $dice as $die )
        {
            $toDisplay .= '<div class="col-xs-1">';
            $toDisplay .= $die->display();
            $toDisplay .= '</div>';
        }

        // Margin
        if ( $marginRight )
        {
            $toDisplay .= '<div class="col-xs-' . $marginRight . '"></div>';
        }
        
        // End row
        $toDisplay .= '</div>';

        echo $toDisplay;
    }
}
