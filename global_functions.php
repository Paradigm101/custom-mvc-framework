<?php

// Convert request type from code to name
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
    }

    // Unknown type
    return NULL;
}

// Convert request type from name to code
function convertRequestTypeToCode( $request_type_name ) {

    switch ( $request_type_name ) {
        case 'page':
            return REQUEST_TYPE_PAGE;
        case 'ajax':
            return REQUEST_TYPE_AJAX;
        case 'api':
            return REQUEST_TYPE_API;
        case 'library':
            return REQUEST_TYPE_LIBRARY;
    }

    // Unknown type
    return NULL;
}
