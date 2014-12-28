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

    public function startGame()
    {
        $this->model->startGame();
    }
    
    public function concedeGame()
    {
        $this->model->concedeGame();
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

        //Update dice with new values
        $this->model->updateDice($newDice);
    }

    private function preProcessAck()
    {
        // 1 Get active player results
        $results = Koth_LIB_Results::getPlayerResults( $this->idUser );

        // 2 Impact DB with results
        // TBD: Manage current experience/level for hero
        $this->model->storeResults( $results );

        // 3 Check victory condition
        if ( $this->model->isVictory() )
        {
            // Close game
            $this->model->closeGame();

            // TBD: display game screen result (manage access/refresh, etc...)
        }

        // 4 Update turn number
        $this->model->updateTurnNumber();

        // 5 Change active player
        $this->model->switchActivePlayer();

        // 6 Reset active player's dice
        $this->model->resetDice();

        // 7 change step to start turn
        $this->model->stepStart();
    }
    
    // Active player ack at the end of his/her turn
    public function processAck()
    {
        $this->preProcessAck();

        // TBD: PvE, have computer play
        if ( true )
        {
            // TBD: play AI: roll and choose (AI/Algo, lots of work)
            $this->roll();
            $this->roll();
            $this->roll();

            // TBD: 1, 2, 3, 4, 5, 6, 7
            $this->preProcessAck();
        }
    }
}
