<?php

// Starting dice
define('KOTH_STARTING_DICE', 4);

// Game statuses
define('KOTH_STATUS_NO_USER',     1);
define('KOTH_STATUS_NOT_STARTED', 2);
define('KOTH_STATUS_RUNNING',     3);
define('KOTH_STATUS_SCORE',       4);

// Game steps
define('KOTH_STEP_START',         'start_turn');
define('KOTH_STEP_AFTER_ROLL_1',  'after_roll_1');
define('KOTH_STEP_AFTER_ROLL_2',  'after_roll_2');
define('KOTH_STEP_END_OF_TURN',   'end_of_turn');

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

        // Game is running
        if ( static::getModel()->isGameActiveForUser() )
        {
            return KOTH_STATUS_RUNNING;
        }

        // Game with scores to ack
        if ( static::getModel()->isGameScoreForUser() )
        {
            return KOTH_STATUS_SCORE;
        }

        // No game running
        return KOTH_STATUS_NOT_STARTED;
    }

    static public function getStep()
    {
        return static::getModel()->getStep();
    }

    // TBD: playtest, magic threshold seems too far compared to attack 60->50
    static public function getMagicThreshold()
    {
        $levels = static::getModel()->getLevels();

        return 80 + 20 * $levels;
    }

    // TBD: playtest, Xp dice seems to be too pricy 15->10
    static public function getXpDicePrice()
    {
        $levels = static::getModel()->getLevels();

        return 20 + $levels;
    }

    static public function canUserRoll()
    {
        return ( ( ( static::getStep() == KOTH_STEP_START )
                || ( static::getStep() == KOTH_STEP_AFTER_ROLL_1 )
                || ( static::getStep() == KOTH_STEP_AFTER_ROLL_2 ) )
              && ( static::isUserActive() ) );
    }

    static public function isUserActive()
    {
        return static::getModel()->isUserActive();
    }

    static public function isUserInactive()
    {
        return static::getModel()->isUserInactive();
    }

    static public function isUserPlaying()
    {
        return static::getModel()->isUserPlaying();
    }

    static public function getResults()
    {
        return static::$model->getResults();
    }

    static public function getIdUserPlayer()
    {
        return static::getModel()->getIdUserPlayer();
    }

    static public function getIdOtherPlayer()
    {
        return static::getModel()->getIdOtherPlayer();
    }

    static public function getIdPlayersByRank()
    {
        return static::getModel()->getIdPlayersByRank();
    }

    static public function getIdActivePlayer()
    {
        return static::getModel()->getIdActivePlayer();
    }

    static public function getIdInactivePlayer()
    {
        return static::getModel()->getIdInactivePlayer();
    }

    static public function setGame( $idUser, $isPvP = true, $isForScore = false )
    {
        static::getModel()->setGame( $idUser, $isPvP, $isForScore );
    }

    static public function startGame( $idUser1, $idHeroMonster1, $idUser2, $idHeroMonster2, $level1 = 0, $level2 = 0 )
    {
        static::getModel()->startGame( $idUser1, $idHeroMonster1, $idUser2, $idHeroMonster2, $level1, $level2 );

        if ( static::getModel()->isActiveAI()
         && !static::getModel()->isEve() )
        {
            static::playAI();
        }

        if ( static::getModel()->isEve() )
        {
            while( !static::getModel()->isGameCompleted() )
            {
                static::playAI();
                static::processEndTurn();
            }
        }
    }

    // AI plays
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
        if ( !static::getModel()->isEve() )
        {
            static::processEndTurn();
        }
    }

    // Roll for active player
    static public function roll( $isAI = false )
    {
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
        // Victory!
        if ( static::getModel()->isVictory() )
        {
            // Active player won the game, Close game
            static::getModel()->activeWinGame();

//            // Close game for EvE as there will be no human action
//            if ( static::getModel()->isEvE() )
//            {
//                static::closeGame();
//            }

            // Nothing else to do
            return;
        }

        // Update game turn number
        // Change active player and update IDs
        // Reset active player's dice to unknown / don't keep
        // Change game step to start turn
        static::getModel()->processEndTurn();

        if ( static::getModel()->isActiveAI()
         && !static::getModel()->isEvE() )
        {
            static::playAI();
        }
    }

    // Player acknowledges end game scores
    static public function closeGame( $idGame )
    {
        static::getModel()->closeGame( $idGame );
    }
    
    /******************************************************************* PvP *******************************************************************/
    
    static public function isPlayingPvP( $idUser )
    {
        return static::getModel()->isPlayingPvP( $idUser );
    }

    static public function removeFromPvPQueue( $idUser )
    {
        return static::getModel()->removeFromPvPQueue( $idUser );
    }

    /*************************** Random ****************************/
    
    static public function isQueuedInRandomPvP( $idUser )
    {
        return static::getModel()->isQueuedInRandomPvP( $idUser );
    }

    static public function queueRandomPvP( $idUser )
    {
        if ( !static::isQueuedInRandomPvP( $idUser ) )
        {
            static::getModel()->queueRandomPvP( $idUser );
        }
    }

    static public function playRandomPvP( $idUser )
    {
        if ( !static::isPlayingPvP( $idUser ) )
        {
            if ( $idOpponent = static::getModel()->getOpponentInRandomPvPQueue() )
            {
                static::removeFromPvPQueue( $idOpponent );
                static::removeFromPvPQueue( $idUser );

                static::startGame( $idUser, rand(1, 6), $idOpponent, rand(1, 6), rand(1, 7), rand(1, 7) );
            }
            else
            {
                static::queueRandomPvP( $idUser );
            }
        }
    }

    /*************************** Hero ****************************/
    
    static public function isQueuedInHeroPvP( $idUser, $idHero )
    {
        return static::getModel()->isQueuedInHeroPvP( $idUser, $idHero );
    }

    static public function queueHeroPvP( $idUser, $idHero )
    {
        if ( !static::isQueuedInHeroPvP( $idUser, $idHero ) )
        {
            static::getModel()->queueHeroPvP( $idUser, $idHero );
        }
    }

    static public function playHeroPvP( $idUser, $idHero )
    {
        if ( !static::isPlayingPvP( $idUser ) )
        {
            if ( $opponent = static::getModel()->getOpponentInHeroPvPQueue( $idUser, $idHero ) )
            {
                static::removeFromPvPQueue( $opponent->idUser );
                static::removeFromPvPQueue( $idUser );

                static::startGame( $idUser, $idHero, $opponent->idUser, $opponent->idHero );
            }
            else
            {
                static::queueHeroPvP( $idUser, $idHero );
            }
        }
    }

    /******************************************************************* PvE *******************************************************************/
    
    static public function isPlayingPvE( $idUser )
    {
        return static::getModel()->isPlayingPvE( $idUser );
    }

    static public function playRandomPvE( $idUser )
    {
        if ( !static::isPlayingPvE( $idUser ) )
        {
            $heroLevel = rand(1, 7);
            $idMonster = static::getModel()->getRandomMonster( $heroLevel );

            static::startGame( $idUser, rand(1, 6), 0, $idMonster, $heroLevel );
        }
    }

}
