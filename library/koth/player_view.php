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

        Log_LIB::trace($data['heroDie']);

        $border        = $player->isActive ? 'border: solid darkblue 2px;' : '';
        $healthColor   = $player->isActive ? 'blue' : 'red';
        $victoryColor  = $player->isActive ? 'green' : '';
        $xpColor       = $player->isActive ? 'purple' : '';
        $xpDisplayed   = $player->currentXP % 15;

        $toDiplay .= '<p style="background-color: LightBlue ;font-size: 20px;' . $border . ';border-radius: 10px;padding: 10px;">' . PHP_EOL
                        . "<strong>User</strong>: {$player->userName} "
                        . "- Hero: {$player->heroName} ({$player->heroLevel}) "
                        . '<span title="Click to see die" onclick="$(\'#heroDie' . $player->isActive . '\').modal(\'show\');" class="glyphicon glyphicon-info-sign"></span>'
                        . ALL_EOL
                        . ALL_EOL
                        . "<span style=\"color: $healthColor;\">Health Points: {$player->currentHP} / {$player->maxHP}</span>" . ALL_EOL
                        . "<span style=\"color: $victoryColor;\">Victory Points: {$player->currentVP} / " . KOTH_VICTORY_WIN . "</span>" . ALL_EOL
                        . "<span style=\"color: $xpColor;\">Experience Points: $xpDisplayed / 15</span>" . ALL_EOL
                        . "Dice pool: {$player->diceNumber}" . ALL_EOL
                    . '</p>';

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
