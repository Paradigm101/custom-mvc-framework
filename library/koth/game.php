<?php

class Koth_LIB_Game
{
    private $idUser;
    private $model;
    
    public function __construct( $idUser )
    {
        $this->idUser = $idUser;
        $this->model = new Koth_LIB_Game_Model( $idUser );
    }

    public function isActive()
    {
        return $this->model->isGameActive();
    }

    public function getUserPlayer()
    {
        return new Koth_LIB_Player( $this->idUser );
    }

    public function getOtherPlayer()
    {
        return new Koth_LIB_Player( $this->idUser, true );
    }

    public function getUserDashboard()
    {
        return new Koth_LIB_Dashboard( $this->idUser );
    }

    public function getBoard()
    {
        return new Koth_LIB_Board( $this->idUser );
    }
    
    public function startGame()
    {
        $this->model->startGame();
    }
    
    public function concedeGame()
    {
        $this->model->concedeGame();
    }

    public function roll()
    {
        // TBD: Check if user can roll DB
        
        // TBD: get number of dice to roll DB
        // TBD: get max values for the different types DB

        // TBD: compute the new dice values

        // TBD: set next step for user DB

        // TBD: update dice with new values DB
    }
}
