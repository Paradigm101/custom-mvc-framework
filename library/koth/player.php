<?php

class Koth_LIB_Player
{
    private $currentHP;
    private $currentVP;
    private $is_active;
    private $is_koth;
    private $roll_done;
    private $user_name;
    private $hero_name;

    public function __construct( $victoryPoints, $healPoints, $is_active, $is_koth, $roll_done, $user_name, $hero_name )
    {
        $this->currentVP = $victoryPoints;
        $this->currentHP = $healPoints;
        $this->is_active = $is_active;
        $this->is_koth   = $is_koth;
        $this->roll_done = $roll_done;
        $this->user_name = $user_name;
        $this->hero_name = $hero_name;
    }

    public function display()
    {
        $toDiplay = "User: {$this->user_name}<br>"
                  . "Hero: {$this->hero_name}<br>"
                  . "Heal Points: {$this->currentHP} / 12<br>"
                  . "Victory Points: {$this->currentVP} / 20<br>"
                  . 'King: ' . ( $this->is_koth ? 'yes' : 'no' ) . '<br>'
                  . 'Active: ' . ( $this->is_active ? 'yes' : 'no' ) . '<br>';
                  
        if ( $this->is_active )
        {
            $toDiplay .= "Roll done: {$this->roll_done} / 3<br>";
        }
        
        return $toDiplay;
    }
}
