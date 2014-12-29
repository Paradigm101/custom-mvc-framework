<?php

class Koth_LIB_Player_View extends Base_LIB_View
{
    public function render()
    {
        $data = $this->getData();

        // No player: Nothing to display
        if ( !( $player = $data['player'] ) )
        {
            echo 'No player';
            return;
        }

        $toDiplay = "User: {$player->userName} - Level: {$player->userLevel}<br>"
                  . "Hero: {$player->heroName} - Level: {$player->heroLevel}<br>"
                  . "Health Points: {$player->currentHP} / {$player->maxHP}<br>"
                  . "Victory Points: {$player->currentVP} / " . KOTH_VICTORY_WIN . "<br>"
                  . "Experience Points: {$player->currentXP}<br>";

        if ( $player->isActive )
        {
            $rollDone = 0;
            switch ( $player->step )
            {
                case KOTH_STEP_AFTER_ROLL_1:
                    $rollDone = 1;
                    break;
                case KOTH_STEP_AFTER_ROLL_2:
                    $rollDone = 2;
                    break;
                case KOTH_STEP_AFTER_ROLL_3:
                    $rollDone = 3;
                    break;
                default:
                    $rollDone = 0;
                    break;
            }

            $toDiplay .= "Roll done: {$rollDone} / 3";
        }

        echo $toDiplay;
    }
}
