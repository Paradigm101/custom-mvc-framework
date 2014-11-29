<?php

define('PROBA_GO_FIRST',      0);
define('PROBA_GO_SECOND',     1);
define('PROBA_PRE_MULLIGAN',  0);
define('PROBA_POST_MULLIGAN', 1);
define('PROBA_KEEP_ONE',      0);
define('PROBA_NO_KEEP',       1);

/**
 * Probabilities page
 */
abstract class Probabilities_PAG_C extends Base_PAG_C {
    
    /**
     *  Probability to NOT draw a card out of a deck
     * 
     * $poolNumber = number of cards to get
     * $totalNumber = number of deck size
     */
    static private function probaNotDraw( $poolNumber, $totalNumber ) {
        
        return ( $totalNumber - $poolNumber ) / $totalNumber;
    }

    // Main method
    static protected function process() {

        // Create a structure to store results
        $tmp   = array( PROBA_NO_KEEP      => array(), PROBA_KEEP_ONE      => array() );
        $tmp   = array( PROBA_PRE_MULLIGAN => $tmp,    PROBA_POST_MULLIGAN => $tmp );
        $proba = array( PROBA_GO_FIRST     => $tmp,    PROBA_GO_SECOND     => $tmp );
        
        // For every 2-drop number possible
        for( $twoDropNumber = 0; $twoDropNumber <= 30; $twoDropNumber++ ) {

            // Pre-mulligan
            // ------------
            // when start, miss 3 times
            $proba[PROBA_GO_FIRST][PROBA_PRE_MULLIGAN][PROBA_NO_KEEP][$twoDropNumber] = static::probaNotDraw($twoDropNumber, 30)
                                                                                      * static::probaNotDraw($twoDropNumber, 29)
                                                                                      * static::probaNotDraw($twoDropNumber, 28);

            // when go second, miss 4 times
            $proba[PROBA_GO_SECOND][PROBA_PRE_MULLIGAN][PROBA_NO_KEEP][$twoDropNumber] = static::probaNotDraw($twoDropNumber, 30)
                                                                                       * static::probaNotDraw($twoDropNumber, 29)
                                                                                       * static::probaNotDraw($twoDropNumber, 28)
                                                                                       * static::probaNotDraw($twoDropNumber, 28);

            // Copy result in keep_one for sanity
            $proba[PROBA_GO_FIRST][PROBA_PRE_MULLIGAN][PROBA_KEEP_ONE][$twoDropNumber]  = $proba[PROBA_GO_FIRST][PROBA_PRE_MULLIGAN][PROBA_NO_KEEP][$twoDropNumber];
            $proba[PROBA_GO_SECOND][PROBA_PRE_MULLIGAN][PROBA_KEEP_ONE][$twoDropNumber] = $proba[PROBA_GO_SECOND][PROBA_PRE_MULLIGAN][PROBA_NO_KEEP][$twoDropNumber];

            // Post-mulligan
            // -------------
            // Go first, no keep: 3 new cards + 2 missed
            $proba[PROBA_GO_FIRST][PROBA_POST_MULLIGAN][PROBA_NO_KEEP][$twoDropNumber] = static::probaNotDraw($twoDropNumber, 30)
                                                                                       * static::probaNotDraw($twoDropNumber, 29)
                                                                                       * static::probaNotDraw($twoDropNumber, 28)
                                                                                       * static::probaNotDraw($twoDropNumber, 27)
                                                                                       * static::probaNotDraw($twoDropNumber, 26);

            // Go second, no keep: 4 new cards + 2 missed
            $proba[PROBA_GO_SECOND][PROBA_POST_MULLIGAN][PROBA_NO_KEEP][$twoDropNumber] = static::probaNotDraw($twoDropNumber, 30)
                                                                                        * static::probaNotDraw($twoDropNumber, 29)
                                                                                        * static::probaNotDraw($twoDropNumber, 28)
                                                                                        * static::probaNotDraw($twoDropNumber, 27)
                                                                                        * static::probaNotDraw($twoDropNumber, 26)
                                                                                        * static::probaNotDraw($twoDropNumber, 25);

            // Go first, 1 keep: 2 new cards + 2 missed
            $proba[PROBA_GO_FIRST][PROBA_POST_MULLIGAN][PROBA_KEEP_ONE][$twoDropNumber] = static::probaNotDraw($twoDropNumber, 29)
                                                                                        * static::probaNotDraw($twoDropNumber, 28)
                                                                                        * static::probaNotDraw($twoDropNumber, 27)
                                                                                        * static::probaNotDraw($twoDropNumber, 26);

            // Go second, 1 keep: 3 new cards + 2 missed
            $proba[PROBA_GO_SECOND][PROBA_POST_MULLIGAN][PROBA_KEEP_ONE][$twoDropNumber] = static::probaNotDraw($twoDropNumber, 29)
                                                                                         * static::probaNotDraw($twoDropNumber, 28)
                                                                                         * static::probaNotDraw($twoDropNumber, 27)
                                                                                         * static::probaNotDraw($twoDropNumber, 26)
                                                                                         * static::probaNotDraw($twoDropNumber, 25);
        }

        static::assign('proba', $proba);
    }
}
