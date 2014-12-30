<?php

class Koth_LIB_Player_View extends Base_LIB_View
{
    public function render()
    {
        $data     = $this->getData();
        $toDiplay = '';

        // No player: Nothing to display
        if ( !( $player = $data['player'] ) )
        {
            echo 'No player';
            return;
        }

        $border            = $player->isActive ? 'border: solid darkblue 2px;' : '';
        $healthPercent     = max( array( floor( $player->currentHP * 100 / $player->maxHP ), 0 ) );
        $victoryPercent    = min( array( floor( $player->currentVP * 100 / KOTH_VICTORY_WIN ), 100 ) );
        $experiencePercent = min( array( floor( ( $player->currentXP % 15 ) * 100 / 15 ), 100 ) );

        $toDiplay .= '<div style="background-color: LightBlue ;font-size: 20px;' . $border . ';border-radius: 10px;padding: 10px;">' . PHP_EOL
                        . "<strong>User</strong>: {$player->userName} - Hero: {$player->heroName} ({$player->heroLevel}) "
                        . " - Die number : {$player->diceNumber}"
                        . '<span style="float:right" title="Click to see distribution" onclick="$(\'#heroDie' . $player->isActive . '\').modal(\'show\');" class="glyphicon glyphicon-info-sign"></span>'
                        . ALL_EOL
                        . ALL_EOL
                        . '<div style="background-color: black; border-radius: 12px; padding: 2px;" title="Health: ' . $player->currentHP . '/' . $player->maxHP . '">'
                                . '<div style="background-color: red;width: ' . $healthPercent . '%;height: 15px;border-radius: 10px;"></div>'
                        . '</div>' . ALL_EOL
                        . '<div style="background-color: black; border-radius: 12px; padding: 2px;" title="Victory: ' . $player->currentVP . '/' . KOTH_VICTORY_WIN . '">'
                                . '<div style="background-color: green;width: ' . $victoryPercent . '%;height: 15px;border-radius: 10px;"></div>'
                        . '</div>' . ALL_EOL
                        . '<div style="background-color: black; border-radius: 12px; padding: 2px;" title="Experience: ' . $player->currentXP % 15 . '/' . 15 . '">'
                                . '<div style="background-color: purple;width: ' . $experiencePercent . '%;height: 15px;border-radius: 10px;"></div>'
                        . '</div>' . ALL_EOL
                    . '</div>';

        $images = '';
        for( $i = 0; $i < 3; $i++ )
        {
            $images .= '<div class="row">';

            foreach ( $data['heroDie'] as $type => $max )
            {
                $imageNameTmp = explode('_', $type);
                $typeTmp = $imageNameTmp[1];
                $value = ( $max - 2 + $i );

                $title = '+' . $value . ' ' . ucfirst($typeTmp);
                $imageName = $imageNameTmp[1] . '_' . ( $max - 2 + $i ) . '.png';

                $images .= '<div class="col-xs-3">
                                <img title="' . $title . '" src="page/koth/image/' . $imageName . '"/>
                            </div>';
            }

            $images .= '</div>';
        }

        $toDiplay .= <<<EOD
<div class="modal fade" id="heroDie{$player->isActive}" tabindex="-1">
    <div class="modal-dialog modal-dialog-center">
        <div class="modal-content">

            <!-- body -->
            <div class="modal-body text-center">
                $images
            </div><!-- end body -->

        </div><!-- end content -->
    </div><!-- end dialog -->
</div><!-- end modal hero die {$player->isActive} -->
EOD;

        echo $toDiplay;
    }
}
