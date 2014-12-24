<?php

class Koth_LIB_Board
{
    private $dice     = array();
    private $rollable = false;

    public function __construct( $idUser )
    {
        // Add player's dice to this board
        if ( $dice = Koth_LIB::getPlayerDice($idUser) )
        {
            // Add dice
            foreach ( $dice as $die )
            {
                $this->dice[] = new Koth_LIB_Die( $die );
            }
        }

        // Check if user can roll dice
        if ( in_array( Koth_LIB::getPlayerStatus($idUser), array( 'before_roll_1', 'after_roll_1', 'after_roll_2' ) ) )
        {
            // Display
            $this->rollable = true;

            // Script
            Page_LIB::addJavascript($this->getScript());
        }
    }

    private function getScript()
    {
        return <<<EOD
// Roll dices
$('#koth_btn_roll').click( function (e)
{
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt: REQUEST_TYPE_AJAX,  // request type
            rn: 'koth_roll'         // request name
        },
        success: function()
        {
            location.reload();
        }
    });
});
EOD;
    }

    public function display()
    {
        // Start row
        $toDisplay  = '<div class="row">';
        
        // Margin left
        $toDisplay .= '<div class="col-xs-1"></div>';

        // Display dice
        $toDisplay .= '<div class="col-xs-6"><div class="row">';
        foreach ( $this->dice as $die )
        {
            $toDisplay .= '<div class="col-xs-2">';
            $toDisplay .= $die->display( $this->rollable );
            $toDisplay .= '</div>';
        }
        $toDisplay .= '</div></div>';

        // Margin in-between
        $toDisplay .= '<div class="col-xs-1"></div>';

        // Button to roll/re-roll
        $toDisplay .= '<div class="col-xs-1" style="height: 100px;">' . "\n"
                        . '<button type="button" class="btn btn-default" id="koth_btn_roll" ' . ( $this->rollable ? '' : 'disabled' ) . '>' . "\n"
                            . '<i class="glyphicon glyphicon-share-alt"></i>&nbsp;Roll' . "\n"
                        . '</button>' . "\n"
                    . '</div>' . "\n";

        // Margin right
        $toDisplay .= '<div class="col-xs-3"></div>';

        // End row
        $toDisplay .= '</div>';

        return $toDisplay;
    }
}
