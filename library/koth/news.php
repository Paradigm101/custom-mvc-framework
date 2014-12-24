<?php

class Koth_LIB_News
{
    private $idUser;

    public function __construct( $idUser )
    {
        $this->idUser = $idUser;
    }

    private function getScriptForAck()
    {
        return <<<EOD
// Roll dices
$('#koth_btn_ack').click( function (e)
{
    e.preventDefault();
    
    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt: REQUEST_TYPE_AJAX,      // request type
            rn: 'koth_ack_after_roll'   // request name
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
        $playerStatus = Koth_LIB::getPlayerStatus($this->idUser);
        
        $message = '';
        $button  = '';

        // Compute message
        if ( $playerStatus == 'after_roll_3' )
        {
            // Retrieve player's results
            list( $attack, $heal, $experience, $victory ) = Koth_LIB::getPlayerResults($this->idUser);

            // User's message
            $message = ( $attack ? "Damages: +$attack<br/>" : '' )
                    . ( $heal ? "Heal: +$heal<br/>" : '' )
                    . ( $experience ? "Experience: +$experience<br/>" : '' )
                    . ( $victory ? "Victory points: +$victory" : '' )
                    . ( $attack ? '<br/>You are the new King of the Hill!' : '' );

            // Button
            $button = '<button type="button" class="btn btn-default" id="koth_btn_ack" >' . "\n"
                        . '<i class="glyphicon glyphicon-ok"></i>' . "\n"
                    . '</button>' . "\n";

            // Script for button
            Page_LIB::addJavascript($this->getScriptForAck());
        }

        // Start
        $toDisplay  = '<div class="row">' . "\n";
        $toDisplay .= '<div class="col-xs-3"></div>' . "\n";
        
        // Message
        $toDisplay .= '<div class="col-xs-3">' . $message . '</div>' . "\n";

        // Action button
        $toDisplay .= '<div class="col-xs-1">' . $button . '</div>' . "\n";

        // End
        $toDisplay .= '<div class="col-xs-5"></div>' . "\n";
        $toDisplay .= '</div>' . "\n";
        
        return $toDisplay;
    }
}
