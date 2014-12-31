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

        $toDiplay .= '<div style="background-color:#4D94FF;font-size: 20px;' . $border . ';border-radius: 10px;padding: 10px;">' . PHP_EOL
                        . "<strong>{$player->userName}</strong> - {$player->heroName} ({$player->heroLevel}) - Dice: {$player->diceNumber}"
                        . '<span style="float:right"
                                 title="Click to see distribution"
                                 onclick="$(\'#heroDie' . $player->isActive . '\').modal(\'show\');"
                                 class="glyphicon glyphicon-question-sign"></span>'
                        . ALL_EOL
                        . ALL_EOL
                        . '<div class="progress" title="Health: ' . $player->currentHP . '/' . $player->maxHP . '">'
                                . '<div class="progress-bar" style="background-image:none;background-color:#E60000;width: ' . $healthPercent . '%;">'
                                    . $player->currentHP . '/' . $player->maxHP
                                . '</div>'
                        . '</div>'
                        . '<div class="progress" title="Victory: ' . $player->currentVP . '/' . KOTH_VICTORY_WIN . '">'
                                . '<div class="progress-bar" style="background-image:none;background-color:#66B366;width:' . $victoryPercent . '%;">'
                                    . $player->currentVP . '/' . KOTH_VICTORY_WIN
                                . '</div>'
                        . '</div>'
                        . '<div class="progress" title="Experience: ' . $player->currentXP % 15 . '/' . 15 . '">'
                                . '<div class="progress-bar" style="background-image:none;background-color:#8D198D;width: ' . $experiencePercent . '%;">'
                                    . $player->currentXP % 15 . '/' . 15
                                . '</div>'
                        . '</div>'
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
