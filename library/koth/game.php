<?php

// Game statuses
define('KOTH_STATUS_NO_USER',     1);
define('KOTH_STATUS_NOT_STARTED', 2);
define('KOTH_STATUS_RUNNING',     3);
define('KOTH_STATUS_FINISHED',    4);

// Game steps
// TBD: remove AFTER_ROLL_3 for END_OF_TURN
define('KOTH_STEP_START',         'start_turn');
define('KOTH_STEP_AFTER_ROLL_1',  'after_roll_1');
define('KOTH_STEP_AFTER_ROLL_2',  'after_roll_2');
define('KOTH_STEP_AFTER_ROLL_3',  'after_roll_3');
define('KOTH_STEP_GAME_FINISHED', 'game_finished');

// TBD: EvE
abstract class Koth_LIB_Game
{
    static private $model;

    static private function getModel()
    {
        if ( !static::$model )
        {
            static::$model = new Koth_LIB_Game_Model( Session_LIB::getUserId() );
        }

        return static::$model;
    }

    static public function getStatus()
    {
        // User is not logged in: can't play
        // TBD: play as a guest?
        if ( !Session_LIB::isUserLoggedIn() )
        {
            return KOTH_STATUS_NO_USER;
        }

        // No game running
        if ( !static::isGameActive() )
        {
            return KOTH_STATUS_NOT_STARTED;
        }

        // Game is finished
        if ( static::getStep() == KOTH_STEP_GAME_FINISHED )
        {
            return KOTH_STATUS_FINISHED;
        }

        // Game is running
        return KOTH_STATUS_RUNNING;
    }

    static public function getStep()
    {
        return static::getModel()->getStep();
    }

    static public function getVictoryThreshold()
    {
        $levels = static::getModel()->getLevels();

        return 60 + 2 * $levels;
    }

    static public function getXpDicePrice()
    {
        $levels = static::getModel()->getLevels();
        
        return 15 + $levels;
    }

    static public function canUserRoll()
    {
        return ( ( ( static::getStep() == KOTH_STEP_AFTER_ROLL_1 )
                || ( static::getStep() == KOTH_STEP_AFTER_ROLL_2 )
                || ( static::getStep() == KOTH_STEP_START ) )
              && ( static::isUserActive() ) );
    }

    static public function isUserActive()
    {
        return static::getModel()->isUserActive();
    }

    static public function isGameActive()
    {
        return static::getModel()->isGameActive();
    }

    static public function isActiveAI()
    {
        return static::getModel()->isActiveAI();
    }

    static public function getResults()
    {
        return static::$model->getResults();
    }
    
    static public function getUserData()
    {
        return static::$model->getUserData();
    }
    
    static public function getHeroData()
    {
        return static::$model->getHeroData();
    }

    // TBD: PvP
    static public function startGame( $heroName = 'attack_health', $heroLevel = 1, $opponentName = '3_3_3_3_1' )
    {
        static::getModel()->startGame( $heroName, $heroLevel, $opponentName );

        // PvE, first turn AI plays
        if ( static::getModel()->isPvE()
         &&  static::isActiveAI() )
        {
            static::playAI();
        }
    }

    // Roll for active player
    // TBD: everything should be done in the same transaction
    static public function roll( $isAI = false )
    {
        // Get max values for the different types
        $maxes = static::getModel()->getHeroMaxValues( $isAI );

        // Compute the new dice values
        $newDice = array();
        for ( $i = 0; $i < static::getModel()->getDiceNumberToRoll(); $i++ )
        {
            $type  = array_rand( $maxes );
            $value = $maxes[ $type ] - rand(0, 2);

            $newDice[] = array( 'type' => $type, 'value' => $value );
        }

        // Force next step (no check/management)
        // TBD: generic add step for every case?
        static::getModel()->addStepForRoll();

        // Update dice with new values
        static::getModel()->updateDice($newDice);

        // Last roll, store results
        if ( static::getStep() == KOTH_STEP_AFTER_ROLL_3 )
        {
            $dbResults = static::getModel()->getPlayerResults();

            $results = array();
            foreach ( $dbResults as $dbResult )
            {
                $results[ $dbResult->name ][] = $dbResult->value;
            }
            
            $results2 = array();
            foreach ( $results as $type => $values )
            {
                $results2[$type] = array_sum( $values );
            }

            // TBD: manage combo sets

            // TBD: manage combo runs?

            // Impact DB with results
            static::getModel()->storeResults( $results2 );
        }
    }

    // TBD: merge with activeWinGame
    static public function userConcedeGame()
    {
        static::getModel()->userConcedeGame();

        // display game screen result (manage access/refresh, etc...)
        static::getModel()->setGameFinished();
    }

    // End of turn is being processed
    static public function processEndTurn()
    {
        // Victory!
        if ( static::getModel()->isVictory() )
        {
            // Active player won the game, Close game
            static::getModel()->activeWinGame();

            // display game screen result (manage access/refresh, etc...)
            static::getModel()->setGameFinished();

            // Nothing else to do
            return;
        }

        // Update turn number
        static::getModel()->updateTurnNumber();

        // Change active player
        static::getModel()->switchActivePlayer();

        // Reset active player's dice
        static::getModel()->resetDice();

        // change step to start turn
        static::getModel()->stepStart();

        // Case PvE AND active is AI, play AI
        if ( static::getModel()->isPvE()
          && static::isActiveAI() )
        {
            static::playAI();
        }
    }

    // AI plays
    // TBD: Manage AI doesn't keep dice according to AI level INSIDE keepDiceAI
    static private function playAI()
    {
        // First roll
        static::roll( true /* $isAI */ );

        // AI keep dice
        static::getModel()->keepDiceAI( 2 /* 2 rolls left */ );
        
        // Second roll
        static::roll( true /* $isAI */ );

        // AI keep dice
        static::getModel()->keepDiceAI( 1 /* 2 rolls left */ );
        
        // Third roll
        static::roll( true /* $isAI */ );

        // After AI plays, process end of turn
        static::processEndTurn();
    }

    // Player acknowledges end game scores
    // TBD: manage PvP
    static public function closeGame()
    {
        static::getModel()->setCompleted();
    }
}
