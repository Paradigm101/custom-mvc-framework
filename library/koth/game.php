<?php

// Starting dice
define('KOTH_STARTING_DICE', 4);

// Game statuses
define('KOTH_STATUS_NO_USER',     1);
define('KOTH_STATUS_NOT_STARTED', 2);
define('KOTH_STATUS_RUNNING',     3);

// Game steps
define('KOTH_STEP_START',         'start_turn');
define('KOTH_STEP_AFTER_ROLL_1',  'after_roll_1');
define('KOTH_STEP_AFTER_ROLL_2',  'after_roll_2');
define('KOTH_STEP_END_OF_TURN',   'end_of_turn');
define('KOTH_STEP_GAME_FINISHED', 'game_finished');

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
        if ( !Session_LIB::isUserLoggedIn() )
        {
            return KOTH_STATUS_NO_USER;
        }

        // No game running
        if ( !static::getModel()->isGameActiveForUser() )
        {
            return KOTH_STATUS_NOT_STARTED;
        }

        // Game is running
        return KOTH_STATUS_RUNNING;
    }

    static public function getStep()
    {
        return static::getModel()->getStep();
    }

    // TBD: playtest, magic threshold seems too far compared to attack 60->50
    static public function getMagicThreshold()
    {
        $levels = static::getModel()->getLevels();

        return 40 + 10 * $levels;
    }

    // TBD: playtest, Xp dice seems to be too pricy 15->10
    static public function getXpDicePrice()
    {
        $levels = static::getModel()->getLevels();

        return 12 + $levels;
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

    static public function getResults()
    {
        return static::$model->getResults();
    }

    // Start EvE
    static public function startGameEvE( $heroName = 'cleric', $heroLevel = 3, $opponentName = '5_3_3_3_1' )
    {
        static::getModel()->startGameEvE( $heroName, $heroLevel, $opponentName );

        static::playAI();
    }

    // Start PvE
    static public function startGamePvE( $heroName = 'cleric', $heroLevel = 3, $opponentName = '5_3_3_3_1' )
    {
        static::getModel()->startGamePvE( $heroName, $heroLevel, $opponentName );

        // PvE, first turn AI plays
        if ( static::getModel()->isPvE()
         &&  static::getModel()->isActiveAI() )
        {
            static::playAI();
        }
    }

    // AI plays
    static private function playAI()
    {
        Log_LIB::trace("playAI");
        
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

    // Roll for active player
    static public function roll( $isAI = false )
    {
        Log_LIB::trace("In roll isAI [$isAI]");

        // Roll and update dice
        static::getModel()->roll( $isAI );

        // Last roll, store results
        if ( static::getStep() == KOTH_STEP_END_OF_TURN )
        {
            $dbResults = Koth_LIB_Player::getResults( static::getModel()->getIdActivePlayer() );

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

            // TBD: manage combo sets/runs?

            // Impact DB with results
            Koth_LIB_Player::storeResults( $results2, static::getModel()->getIdActivePlayer() );
        }
    }

    // User concede game
    static public function userConcedeGame()
    {
        static::getModel()->userConcedeGame();
    }

    // Process end of turn
    static public function processEndTurn()
    {
        Log_LIB::trace("ProcessEndTurn");
        
        // Victory!
        if ( static::getModel()->isVictory() )
        {
            // Active player won the game, Close game
            static::getModel()->activeWinGame();

            // Nothing else to do
            return;
        }

        static::getModel()->processEndTurn();
        
        // Case PvE AND active is AI, play AI
        if ( static::getModel()->isPvE()
          && static::getModel()->isActiveAI() )
        {
            static::playAI();
        }
    }

    // Player acknowledges end game scores
    // TBD: manage PvP
    static public function closeGame()
    {
        Log_LIB::trace("closeGame");
        static::getModel()->closeGame();
    }
}
