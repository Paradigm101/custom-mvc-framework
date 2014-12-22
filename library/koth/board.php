<?php

class Koth_LIB_Board
{
    private $dice = array();
    
    public function addDie( $die )
    {
        $this->dice[] = $die;
    }

    private $diceNumber = 6;
    
    public function __construct( $diceNumber = 6 ) {
        $this->diceNumber = $diceNumber;
    }
    
    public function display()
    {
        // Start row
        $toDisplay  = '<div class="row">';
        
        // Margin left
        $toDisplay .= '<div class="col-xs-1"></div>';

        // Button to roll/re-roll
        $toDisplay .= '<div class="col-xs-1" style="height: 100px;">'
                        . '<button type="button" class="btn btn-default" id="koth_btn_board_roll" onclick="alert(\'rolling\');">'
                            . '<i class="glyphicon glyphicon-share-alt"></i>&nbsp;Roll'
                        . '</button>'
                    . '</div>';

        // Margin in-between
        $toDisplay .= '<div class="col-xs-1"></div>';

        // Display dice
        $toDisplay .= '<div class="col-xs-6"><div class="row">';
        foreach ( $this->dice as $die )
        {
            $toDisplay .= '<div class="col-xs-2">';
            $toDisplay .= $die->display();
            $toDisplay .= '</div>';
        }
        $toDisplay .= '</div></div>';

        // Margin right
        $toDisplay .= '<div class="col-xs-3"></div>';

        // End row
        $toDisplay .= '</div>';

        return $toDisplay;
    }
}
