<?php

class Koth_LIB_Board_View extends Base_LIB_View
{
    public function render()
    {
        $data = $this->getData();

        // Start row
        $toDisplay  = '<div class="row">';
        
        // Margin left
        $toDisplay .= '<div class="col-xs-1"></div>';

        // Display dice
        $toDisplay .= '<div class="col-xs-6"><div class="row">';
        foreach ( $data['dice'] as $dataDie )
        {
            $die = new Koth_LIB_Die( $dataDie, $data['rollable'] );

            $toDisplay .= '<div class="col-xs-2">';
            $toDisplay .= $die->display();
            $toDisplay .= '</div>';
        }
        $toDisplay .= '</div></div>';

        // Margin in-between
        $toDisplay .= '<div class="col-xs-1"></div>';

        // Button to roll/re-roll
        $toDisplay .= '<div class="col-xs-1" style="height: 100px;">' . "\n";
        if ( $data['rollable'] )
        {
            $toDisplay .= '<button type="button" class="btn btn-default" id="koth_btn_roll">' . "\n"
                            . '<i class="glyphicon glyphicon-share-alt"></i>&nbsp;Roll' . "\n"
                        . '</button>' . "\n";
        }
        $toDisplay .= '</div>' . "\n";

        // Margin right
        $toDisplay .= '<div class="col-xs-3"></div>';

        // End row
        $toDisplay .= '</div>';

        echo $toDisplay;
    }
}
