<?php

/**
 * Wide range of tools
 */
abstract class Tools_LIB {

    // Secure cloning for those deep references
    static public function safeClone($data)
    {
        return unserialize( serialize( $data ) );
    }

    // Return microtimes difference with microtime format (non-float)
    static public function getMicroTimeDiff( $microTimeAfter, $microTimeBefore ) {
        
        return array( ( $microTimeAfter[0] - $microTimeBefore[0] ), ( $microTimeAfter[1] - $microTimeBefore[1]) );
    }
}
