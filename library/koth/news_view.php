<?php

class Koth_LIB_News_View extends Base_LIB_View
{
    // TBD: manage first turn, only unknown dice
    private function displayResults( $results )
    {
        return ( array_key_exists('attack', $results)     ? "Damages: +"        . $results['attack'] . ALL_EOL : '' )
             . ( array_key_exists('health', $results)     ? "Health: +"         . $results['health'] . ALL_EOL : '' )
             . ( array_key_exists('experience', $results) ? "Experience: +"     . $results['experience'] . ALL_EOL : '' )
             . ( array_key_exists('victory', $results)    ? "Victory points: +" . $results['victory'] . ALL_EOL : '' );
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
                    $message = '<strong>Other player\'s results</strong>:' . ALL_EOL
                            . $this->displayResults($data['other_results']);

                    // Button
                    $button = '<button type="button" class="btn btn-default" id="koth_btn_roll">' . "\n"
                                . '<i class="glyphicon glyphicon-share-alt"></i>&nbsp;Roll' . "\n"
                            . '</button>' . "\n";
                    break;

                case KOTH_STEP_AFTER_ROLL_1:
                    // Message
                    $message = 'Two rolls left, click on a die to reroll it.';

                    // Button
                    $button = '<button type="button" class="btn btn-default" id="koth_btn_roll">' . "\n"
                                . '<i class="glyphicon glyphicon-share-alt"></i>&nbsp;Roll' . "\n"
                            . '</button>' . "\n";
                    break;

                case KOTH_STEP_AFTER_ROLL_2:
                    // Message
                    $message = 'One roll left, click on a die to reroll it.';

                    // Button
                    $button = '<button type="button" class="btn btn-default" id="koth_btn_roll">' . "\n"
                                . '<i class="glyphicon glyphicon-share-alt"></i>&nbsp;Roll' . "\n"
                            . '</button>' . "\n";
                    break;

                case KOTH_STEP_AFTER_ROLL_3:
                    // Message
                    $message = '<strong>Your results</strong>:' . ALL_EOL
                             . $this->displayResults($data['player_results']);

                    // Button
                    $button = '<button type="button" class="btn btn-default" id="koth_btn_news_ack" >' . "\n"
                                . '<i class="glyphicon glyphicon-ok"></i>&nbsp;End turn' . "\n"
                            . '</button>' . "\n";
                    break;
                default:
                    $message = 'WTF, new step???';
                    break;
            }
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
        
        echo $toDisplay;
    }
}
