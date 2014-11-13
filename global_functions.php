<?php

// Convert request type from code to name
//  to convert url to directory name
function convertRequestTypeToName( $request_type_code ) {

    switch ( $request_type_code ) {
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

    // Unknown type
    return NULL;
}

// Convert request type from name to code
function convertRequestTypeToCode( $request_type_name ) {

    switch ( strtolower($request_type_name) ) {
        case 'page':
            return REQUEST_TYPE_PAGE;
        case 'ajax':
            return REQUEST_TYPE_AJAX;
        case 'api':
            return REQUEST_TYPE_API;
        case 'library':
            return REQUEST_TYPE_LIBRARY;
        case 'table':
            return REQUEST_TYPE_TABLE;
    }

    // Unknown type
    return NULL;
}

// Get file from a class name, for autoload and security purpose
//      className = <Module>_<RequestType>_<Complement>
//      Modules can have underscore(s)
//      Request Type : PAG, AJA, API, LIB, TAB
//      Complement: if request type is PAG, AJA, API then _M, _V, _C
//           else can be empty or anything except a request type (PAG, AJA, API, LIB, TAB)
function getFileForClass( $className, $isSecurity = false ) {

    // Parse out class name
    $exploded = explode('_', $className);

    // Retrieve last element
    $last = strtolower( array_pop($exploded) );

    // Not a controller => no security file
    if ( $isSecurity && $last != 'c' ) {

        return null;
    }

    // If last element is a request type, no complement
    if ( in_array($last, array('pag', 'aja', 'api', 'lib', 'tab'))) {

        $complement = '';
        $requestType = $last;
    }
    // Last element is the complement and next retrive request type
    else {
        $complement  = $last;
        $requestType = strtolower( array_pop($exploded) );
    }

    // Retrieve module name (can have underscores)
    $module = strtolower( implode('_', $exploded) );

    // Convert request type into folder name
    switch ( $requestType ) {
        case 'pag': $requestTypeName = 'page';       break;
        case 'aja': $requestTypeName = 'ajax';       break;
        case 'api': $requestTypeName = 'api';        break;
        case 'lib': $requestTypeName = 'library';    break;
        case 'tab': $requestTypeName = 'table';      break;
        default:
            file_put_contents(LOG_FILE, "[getFileFromClass] Unknown request type [$requestType] for [$className]\n", FILE_APPEND);
            return null;
    }

    // Complement
    switch ( $requestCode = convertRequestTypeToCode( $requestTypeName ) ) {

        // Case MVC
        case REQUEST_TYPE_PAGE:
        case REQUEST_TYPE_AJAX:
        case REQUEST_TYPE_API:
            
            // Case security file
            if ( $isSecurity && $last == 'c' ) {

                $fileName = $module . '.xml';
            }
            else {
                $fileName = $module . '_' . $complement . '.php';
            }
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
            file_put_contents(LOG_FILE, "[getFileFromClass] Unknown request code [$requestCode] for request [$requestTypeName] in [$className]\n", FILE_APPEND);
            return null;
    }

    // At last, return file name
    return $requestTypeName . '/' . $module . '/' . $fileName;
}
