<?php

class Koth_LIB_News_View extends Base_LIB_View
{
    // TBD: manage first turn, only unknown dice
    private function displayResults( $results )
    {
        return ( array_key_exists('attack', $results)     ? "<span style=\"color: red;\">Damages: +"        . $results['attack'] . '</span>' . ALL_EOL : '' )
             . ( array_key_exists('health', $results)     ? "<span style=\"color: blue;\">Health: +"         . $results['health'] . '</span>' . ALL_EOL : '' )
             . ( array_key_exists('experience', $results) ? "<span style=\"color: purple;\">Experience: +"     . $results['experience'] . '</span>' . ALL_EOL : '' )
             . ( array_key_exists('victory', $results)    ? "<span style=\"color: limegreen;\">Victory points: +" . $results['victory'] . '</span>' . ALL_EOL : '' );
    }
    
    public function render()
    {
        $data = $this->getData();

        // Init
        $message = 'Un-initialized';
        $button  = '';

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
                    if ( $results = $this->displayResults($data['other_results']) )
                    {
                        $message = '<strong>Other player\'s results</strong>:' . ALL_EOL
                                . $results;
                    }
                    else
                    {
                        $message = 'This is the first turn, you have less dice only for this turn.';
                    }

                    // Button
                    $button = '<button type="button" class="btn btn-default" id="koth_btn_roll">' . "\n"
                                . '<i class="glyphicon glyphicon-share"></i>&nbsp;Roll' . "\n"
                            . '</button>' . "\n";
                    break;

                case KOTH_STEP_AFTER_ROLL_1:
                    // Message
                    $message = 'Two rolls left, click on a die to reroll it.';

                    // Button
                    $button = '<button type="button" class="btn btn-default" id="koth_btn_roll">' . "\n"
                                . '<i class="glyphicon glyphicon-share"></i>&nbsp;Roll' . "\n"
                            . '</button>' . "\n";
                    break;

                case KOTH_STEP_AFTER_ROLL_2:
                    // Message
                    $message = 'One roll left, click on a die to reroll it.';

                    // Button
                    $button = '<button type="button" class="btn btn-default" id="koth_btn_roll">' . "\n"
                                . '<i class="glyphicon glyphicon-share"></i>&nbsp;Roll' . "\n"
                            . '</button>' . "\n";
                    break;

                case KOTH_STEP_AFTER_ROLL_3:
                    // Message
                    $message = '<strong>Your results</strong>:' . ALL_EOL
                             . $this->displayResults($data['player_results']);

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

        // Start
        $toDisplay  = '<div class="row">' . "\n";
        $toDisplay .= '<div class="col-xs-2"></div>' . "\n";
        
        // Message
        $toDisplay .= '<div class="col-xs-6"><p style="font-size: 20px;height: 90px;">' . $message . '</p></div>' . "\n";

        // Action button
        $toDisplay .= '<div class="col-xs-1">' . $button . '</div>' . "\n";

        // End
        $toDisplay .= '<div class="col-xs-3"></div>' . "\n";
        $toDisplay .= '</div>' . "\n";
        
        echo $toDisplay;
    }
}
