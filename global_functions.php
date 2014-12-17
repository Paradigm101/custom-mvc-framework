<?php

// Convert request type from code to directory
function convertRequestCodeToDirectory( $requestTypeCode ) {

    switch ( $requestTypeCode ) {
        case REQUEST_TYPE_PAGE:
            return 'page';
        case REQUEST_TYPE_AJAX:
            return 'ajax';
        case REQUEST_TYPE_API:
            return 'api';
        case REQUEST_TYPE_LIBRARY:
            return 'library';
    }

    // Unknown type
    return null;
}

// Convert request type from code to class
function convertRequestCodeToClass( $requestTypeCode ) {

    // Simple transformation
    return strtoupper( substr( convertRequestCodeToDirectory( $requestTypeCode ), 0, 3 ) );
}

// Convert request type from class to code
function convertRequestClassToCode( $requestTypeClass ) {

    switch ( strtolower($requestTypeClass) ) {
        case 'pag':
            return REQUEST_TYPE_PAGE;
        case 'aja':
            return REQUEST_TYPE_AJAX;
        case 'api':
            return REQUEST_TYPE_API;
        case 'lib':
            return REQUEST_TYPE_LIBRARY;
    }

    // Unknown type
    return null;
}

// Get file to load a class
//      className = <Module>_<RequestType>_<Complement>
//      Modules and Complements can have underscore(s)
//      Request Type : PAG, AJA, API, LIB (reserved keywords)
//      Complement: if request type is PAG, AJA, API then _M, _V, _C
//           else can be any value or empty
function getFileForClass( $className ) {

    // Parse out class name
    $exploded = explode('_', $className);
    
    // Create complement
    $complement = array();
    
    // While last element is not a class type, store it in complement
    while ( ( $requestTypeCode = convertRequestClassToCode( $last = strtolower( array_pop($exploded) ) ) ) == null ) {

        // Arriving at the end of the class without finding a request type code
        if ( !$last ) {
            return null;
        }
//        file_put_contents(LOG_FILE, "[getFileForClass] last [" . print_r($last, true) . "]\n", FILE_APPEND);
        // In the good order
        array_unshift($complement, $last);
    }

    // Build complement
    $complement = strtolower( implode('_', $complement) );

    // Retrieve module name (can have underscores)
    $module = strtolower( implode('_', $exploded) );

    // Complement
    switch ( $requestTypeCode ) {

        // Case MVC
        case REQUEST_TYPE_PAGE:
        case REQUEST_TYPE_AJAX:
        case REQUEST_TYPE_API:

            $fileName = $module . '_' . $complement . '.php';
            break;

        // Other case, default file name is module else name is complement
        case REQUEST_TYPE_LIBRARY:
            if ( $complement ) {
                $fileName = $complement . '.php';
            }
            else {
                $fileName = $module . '.php';
            }
            break;
        default:
            file_put_contents(LOG_FILE, "[getFileForClass] Unknown request code [$requestTypeCode] for class [$className]\n", FILE_APPEND);
            return null;
    }

    // Get directory
    if ( ( $directory = convertRequestCodeToDirectory($requestTypeCode) ) == null ) {

        file_put_contents(LOG_FILE, "[getFileForClass] Can not get directory for class [$className] with request type code [$requestTypeCode]\n", FILE_APPEND);
        return null;
    }

    // At last, return file name
    return $directory . '/' . $module . '/' . $fileName;
}

// Check if this class exists
function isClassExists( $className ) {
    return file_exists( getFileForClass( $className ) );
}
