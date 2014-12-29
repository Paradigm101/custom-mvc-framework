<?php

class Koth_LIB_Board_View extends Base_LIB_View
{
    public function render()
    {
        $data = $this->getData();

        // Start row
        $toDisplay  = '<div class="row">';
        
        // Margin
        $toDisplay .= '<div class="col-xs-1" style="height: 85px;"></div>';

        // Display dice
        $toDisplay .= '<div class="col-xs-10"><div class="row">';
        foreach ( $data['dice'] as $dataDie )
        {
            $die = new Koth_LIB_Die( $dataDie, $data['rollable'] );

            $toDisplay .= '<div class="col-xs-1">';
            $toDisplay .= $die->display();
            $toDisplay .= '</div>';
        }
        $toDisplay .= '</div></div>';

        // Margin
        $toDisplay .= '<div class="col-xs-1"></div>';

        // End row
        $toDisplay .= '</div>';

        echo $toDisplay;
    }
}
