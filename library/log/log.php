<?php

/**
 * Log services: show or trace
 */
abstract class Log_LIB {

    // Get EOL according to output type
    static private function getEOL( $isWeb ) {

        return ( $isWeb ? ALL_EOL : PHP_EOL );
    }

    // Log data according to type
    static private function coreLog( $param, $title, $isWeb = false ) {

        // Specific display according to data type
        switch ( strtolower( gettype( $param ) ) ) {
            
            case 'array':
            case 'object':
                if ( $isWeb ) {
                    ob_start();
                    var_dump( $param );                 // TBD: use safeClone?
                    $content = ob_get_contents();
                    ob_end_clean();
                }
                else {
                    $content = print_r( $param, true ); // TBD: use safeClone?
                }
                break;

            case 'string':
                $content = '"' . addslashes( $param ) . '"';
                break;

            case 'boolean':
                $content = $param ? 'TRUE' : 'FALSE';
                break;

            case 'null':
                $content = 'NULL';
                break;

            case 'resource':
                $content = '[R#' . get_resource_type( $param ) . ']';
                break;

            case 'integer':
            case 'double':
                $content = $param;
                break;

            default:
                $content = '[Unknown type : ' . gettype( $param ) . ']';
        }

        // Manage title
        if ( $title ) {
            $content = "$title : " . $content;
        }

        // Note: Using a string here to prevent loss of precision
        // in case of "overflow" (PHP converts it to a double)
        $comps = explode(' ', microtime());
        $micro = sprintf('%06d', $comps[0] * 1000000);

        // Add date
        $content = '[' . date('H:i:s') . ":$micro] " . $content;

        return $content . static::getEOL($isWeb);
    }

    // Show log in the browser
    static public function show( $param, $title = null ) {

        // Launch the big thing
        echo static::coreLog( $param, $title, true /* IsWeb */ );
    }

    // Log data in file
    static public function trace( $param, $title = null ) {

        // Launch the big thing
        file_put_contents(LOG_FILE, static::coreLog($param, $title), FILE_APPEND);
    }

    // Trace backtrace for debug
    static public function traceBT($title = null) {

        // Get backtrace
        $backTrace = debug_backtrace();

        // Remove first element
        unset($backTrace[0]);

        // Remove object property for lisibility
        foreach ( $backTrace as &$element ) {
            unset( $element['object'] );
        }

        // Trace backtrace
        static::trace($backTrace, $title);
    }
}
