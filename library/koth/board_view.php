<?php

class Koth_LIB_Board_View extends Base_LIB_View
{
    // With space: extra space at the bottom for presentation
    private function displayDice( $inputDice, $rollable = false, $withSpace = true )
    {
        Log_LIB::trace($inputDice);
        $toDisplay = '';

        // Get dice objects
        $dice = array();
        foreach ( $inputDice as $dataDie )
        {
            // Create die
            $dice[] = new Koth_LIB_Die( $dataDie, $rollable );
        }

        // TBD: manage more than 12 dice
        $marginLeft = floor( ( 12 - count( $dice ) ) / 2 );
        $marginRight = ceil( ( 12 - count( $dice ) ) / 2 );

        $height = ( $withSpace ? '80' : '60' );
        
        // Start row (height is for bigger images on hover
        $toDisplay .= '<div class="row" style="height:' . $height . 'px;">';

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
        
        return $toDisplay;
    }
    
    // TBD: manage more than 12 dice
    // TBD: manage image on hover not centered
    public function render()
    {
        // Init
        $data = $this->getData();
        $toDisplay = '';

        // Display dice
        $toDisplay .= $this->displayDice($data['activeDice'], $data['rollable']);

        // TBD: player is inactive (PvP)
        if ( !$data['is_active'] )
        {
            $message = 'Non-active player';
        }
        else
        {
            switch ( $data['step'] )
            {
                case KOTH_STEP_START:
                    // Message
                    if ( $data['isFirstPlayerFirstTurn'] )
                    {
                        $message = '<div class="text-center" style="font-size: 20px;height: 110px;">This is the first turn, you have less dice only for this turn.</div>';
                    }
                    else
                    {
                        $message = $this->displayDice($data['nonActiveDice'], false, false ) . ALL_EOL
                             . '<div class="text-center" style="font-size: 20px;">(Opponent\'s results)</div>'
                            . '';
                    }

                    // Button
                    $button = '<button type="button" class="btn btn-default" id="koth_btn_roll">' . "\n"
                                . '<i class="glyphicon glyphicon-share"></i>&nbsp;Roll' . "\n"
                            . '</button>' . "\n";
                    break;

                case KOTH_STEP_AFTER_ROLL_1:
                    // Message
                    $message = '<div class="text-center" style="font-size: 20px;height: 110px;">Two rolls left, click on a die to reroll it.</div>';

                    // Button
                    $button = '<button type="button" class="btn btn-default" id="koth_btn_roll">' . "\n"
                                . '<i class="glyphicon glyphicon-share"></i>&nbsp;Roll' . "\n"
                            . '</button>' . "\n";
                    break;

                case KOTH_STEP_AFTER_ROLL_2:
                    // Message
                    $message = '<div class="text-center" style="font-size: 20px;height: 110px;">One roll left, click on a die to reroll it.</div>';

                    // Button
                    $button = '<button type="button" class="btn btn-default" id="koth_btn_roll">' . "\n"
                                . '<i class="glyphicon glyphicon-share"></i>&nbsp;Roll' . "\n"
                            . '</button>' . "\n";
                    break;

                case KOTH_STEP_AFTER_ROLL_3:
                    // Message
                    $message = '<div class="text-center" style="font-size: 20px;height: 110px;">Great turn, click end turn to see what your opponent will do.</div>';

                    // Button
                    $button = '<button type="button" class="btn btn-default" id="koth_btn_news_ack" >' . "\n"
                                . '<i class="glyphicon glyphicon-check"></i>&nbsp;End turn' . "\n"
                            . '</button>' . "\n";
                    break;
                default:
                    $message = 'WTF, new step???';
                    break;
            }
        }

        // Action button
        $toDisplay .= '<div class="text-center">' . $button . '</div>' . ALL_EOL . ALL_EOL . ALL_EOL;
        $toDisplay .= $message;
        
        echo $toDisplay;
    }
}
