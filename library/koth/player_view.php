<?php

// TBD: vertical progress bar for magic?
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

        $border            = $player->isActive ? 'border: solid darkblue 2px;' : 'border: solid #D8DFE6 2px;';
        $healthPercent     = max( array( floor( $player->currentHP * 100 / $player->maxHP ), 0 ) );
        $magicPercent      = min( array( floor( $player->currentMP * 100 / Koth_LIB_Game::getMagicThreshold() ), 100 ) );
        $experiencePercent = min( array( floor( ( $player->currentXP % Koth_LIB_Game::getXpDicePrice() ) * 100 / Koth_LIB_Game::getXpDicePrice() ), 100 ) );

        $toDiplay .= '<div style="background-color:AliceBlue ;font-size: 20px;' . $border . ';border-radius: 10px;padding: 10px;">' . PHP_EOL
                        . "<strong>{$player->userName}</strong> ({$player->userLevel}) - {$player->heroName} ({$player->heroLevel}) " . "- {$player->diceNumber} dice"
                        . '<span style="float:right"
                                 title="Click to see distribution"
                                 onclick="$(\'#heroDie' . $player->isActive . '\').modal(\'show\');"
                                 class="glyphicon glyphicon-question-sign"></span>'
                        . ALL_EOL
                        . ALL_EOL
                        . '<div class="progress" title="Health ' . max( $player->currentHP, 0 ) . '/' . $player->maxHP . '">'
                                . '<div class="progress-bar" style="background-image:none;background-color:#E60000;width: ' . $healthPercent . '%;">'
                                    . max( $player->currentHP, 0 ) . '/' . $player->maxHP
                                . '</div>'
                        . '</div>'
                        . '<div class="progress" title="Magic ' . min( $player->currentMP, Koth_LIB_Game::getMagicThreshold() ) . '/' . Koth_LIB_Game::getMagicThreshold() . '">'
                                . '<div class="progress-bar" style="background-image:none;background-color:DeepSkyBlue;width:' . $magicPercent . '%;">'
                                    . min( $player->currentMP, Koth_LIB_Game::getMagicThreshold() ) . '/' . Koth_LIB_Game::getMagicThreshold()
                                . '</div>'
                        . '</div>'
                        . '<div class="progress" title="Experience ' . $player->currentXP % Koth_LIB_Game::getXpDicePrice() . '/' . Koth_LIB_Game::getXpDicePrice() . '">'
                                . '<div class="progress-bar" style="background-image:none;background-color:#8D198D;width: ' . $experiencePercent . '%;">'
                                    . $player->currentXP % Koth_LIB_Game::getXpDicePrice() . '/' . Koth_LIB_Game::getXpDicePrice()
                                . '</div>'
                        . '</div>'
                    . '</div>';

        /****************************************** DISPLAY DIE DISTRIBUTION ***********************************************************/
        // TBD: use koth_lib_die instead of hard-coding images
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
