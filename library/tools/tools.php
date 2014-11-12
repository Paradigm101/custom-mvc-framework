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
}
