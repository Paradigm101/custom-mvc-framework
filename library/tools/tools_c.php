<?php

/**
 * Wide range of tools
 */
abstract class Tools_Library_Controller {

    // Secure cloning for those deep references
    static public function safeClone($data)
    {
        return unserialize( serialize( $data ) );
    }
}
