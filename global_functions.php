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
        case REQUEST_TYPE_TABLE:
            return 'table';
    }

    // Log problem
    file_put_contents(LOG_FILE, "[convertRequestCodeToDirectory] Unknown type code [$requestTypeCode]\n", FILE_APPEND);

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
        case 'tab':
            return REQUEST_TYPE_TABLE;
    }

    // Log problem
    file_put_contents(LOG_FILE, "[convertRequestClassToCode] Unknown request [$requestTypeClass]\n", FILE_APPEND);

    // Unknown type
    return null;
}

// Get file to load a class
//      className = <Module>_<RequestType>_<Complement>
//      Modules can have underscore(s)
//      Request Type : PAG, AJA, API, LIB, TAB
//      Complement: if request type is PAG, AJA, API then _M, _V, _C
//           else can be empty or anything except a request type (PAG, AJA, API, LIB, TAB)
function getFileForClass( $className ) {

    // Parse out class name
    $exploded = explode('_', $className);

    // Retrieve last element
    $last = strtolower( array_pop($exploded) );

    // If last element is a request type, no complement
    if ( in_array($last, array('lib', 'tab'))) {

        $complement       = '';
        $requestTypeClass = $last;
    }
    // Last element is the complement and next retrive request type
    else {
        $complement       = $last;
        $requestTypeClass = strtolower( array_pop($exploded) );
    }

    // Retrieve module name (can have underscores)
    $module = strtolower( implode('_', $exploded) );

    // Get request code
    $requestTypeCode = convertRequestClassToCode($requestTypeClass);

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
        case REQUEST_TYPE_TABLE:
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
    $directory = convertRequestCodeToDirectory($requestTypeCode);

    // At last, return file name
    return $directory . '/' . $module . '/' . $fileName;
}

// Get security xml file associated with class (only for controllers)
function getSecurityFileForClass( $className ) {

    // Not a controller, no security file
    if ( substr( $className, -2, 2 ) != '_C' ) {

        return null;
    }

    // Return xml file
    return str_replace( '_c.php', '.xml', getFileForClass($className) );
}
