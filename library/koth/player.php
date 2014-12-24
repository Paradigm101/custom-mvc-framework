<?php

class Koth_LIB_Player
{
    private $currentHP;
    private $currentVP;
    private $is_active;
    private $is_koth;
    private $status;
    private $user_name;
    private $hero_name;

    public function __construct( $player )
    {
        $this->currentVP = $player->VP;
        $this->currentHP = $player->HP;
        $this->is_active = $player->is_active;
        $this->is_koth   = $player->is_koth;
        $this->status    = $player->status;
        $this->user_name = $player->user_name;
        $this->hero_name = $player->hero_name;
    }

    public function display()
    {
        $toDiplay = "User: {$this->user_name} - "
                  . "Hero: {$this->hero_name}<br>"
                  . "Heal Points: {$this->currentHP} / 12 - "
                  . "Victory Points: {$this->currentVP} / 20<br>"
                  . 'Active: ' . ( $this->is_active ? 'yes' : 'no' );

        if ( $this->is_active )
        {
            $rollDone = 0;
            switch ( $this->status )
            {
                case 'after_roll_1':
                    $rollDone = 1;
                    break;
                case 'after_roll_2':
                    $rollDone = 2;
                    break;
                case 'after_roll_3':
                    $rollDone = 3;
                    break;
                default:
                    $rollDone = 0;
            }

            $toDiplay .= " - Roll done: {$rollDone} / 3";
        }

        $toDiplay .= '<br>'
                   . 'King: ' . ( $this->is_koth ? 'yes' : 'no' ) . '<br>';

        return $toDiplay;
    }
}
