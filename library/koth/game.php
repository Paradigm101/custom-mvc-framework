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

    public function getStep()
    {
        return $this->model->getStep();
    }
    
    public function isUserActive()
    {
        return $this->model->isUserActive();
    }

    public function isGameActive()
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

    public function getGameScores()
    {
        return new Koth_LIB_Scores( $this->idUser );
    }

    public function getUserDashboard()
    {
        return new Koth_LIB_Dashboard( $this->idUser );
    }

    public function getBoard()
    {
        return new Koth_LIB_Board( $this->idUser );
    }

    public function getNews()
    {
        return new Koth_LIB_News( $this->idUser );
    }

    // AI plays
    // TBD: Improve algo
    private function playAI()
    {
        $this->roll();
        if ( KOTH_AI_LEVEL )
        {
            $this->model->keepDiceAI( 2 /* 2 rolls left */ );
        }
        $this->roll();
        if ( KOTH_AI_LEVEL )
        {
            $this->model->keepDiceAI( 1 /* 2 rolls left */ );
        }
        $this->roll();
        $this->preProcessAck();
    }

    public function startGame()
    {
        $this->model->startGame();

        // PvE, have computer play if init
        if ( $this->model->isPvE() && !$this->model->isUserActive() )
        {
            $this->playAI();
        }
    }

    public function userConcedeGame()
    {
        $this->model->userConcedeGame();
        
        // display game screen result (manage access/refresh, etc...)
        $this->model->setGameFinished();
    }

    // TBD: everything should be done in the same transaction
    // Roll for active player
    public function roll()
    {
        // Get max values for the different types
        $maxes = $this->model->getHeroMaxValues();

        // Compute the new dice values
        $newDice = array();
        for ( $i = 0; $i < $this->model->getDiceNumberToRoll(); $i++ )
        {
            $type  = array_rand( $maxes );
            $value = $maxes[ $type ] - rand(0, 2);

            $newDice[] = array( 'type' => $type, 'value' => $value );
        }

        // Force next step (no check/management)
        // TBD: generic add step for every case?
        $this->model->addStepForRoll();

        // Update dice with new values
        $this->model->updateDice($newDice);
        
        if ( $this->getStep() == KOTH_STEP_AFTER_ROLL_3 )
        {
            // Get active player results
            $results = Koth_LIB_Results::getPlayerResults( $this->idUser );

            // Impact DB with results
            $this->model->storeResults( $results );
        }
    }

    public function isGameFinished()
    {
        return ( $this->model->getStep() == KOTH_STEP_GAME_FINISHED );
    }

    private function preProcessAck()
    {
        // Check victory condition
        if ( $this->model->isVictory() )
        {
            // Close game
            $this->model->activeWinGame();

            // display game screen result (manage access/refresh, etc...)
            $this->model->setGameFinished();

            // Then leave and return isVictory
            return true;
        }

        // Update turn number
        $this->model->updateTurnNumber();

        // Change active player
        $this->model->switchActivePlayer();

        // Reset active player's dice
        $this->model->resetDice();

        // change step to start turn
        $this->model->stepStart();
        
        // Return no victory
        return false;
    }

    // Active player ack at the end of his/her turn
    public function processEndTurn()
    {
        $isVictory = $this->preProcessAck();
        
        // In case of victory, nothing else to do
        if ( $isVictory )
        {
            return;
        }

        // PvE, have computer play
        if ( $this->model->isPvE() )
        {
            $this->playAI();
        }
    }

    public function closeGame()
    {
        $this->model->setCompleted();
    }
}
