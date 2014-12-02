<?php

// Users management model
class Users_PAG_M extends Base_LIB_Model {
    
    // Get data to display
    public function getData() {

        // Sort
        if ( $sort = Url_LIB::getRequestParam('s') ) {
            $sortQuery = ( $sort[0] == '_' ? substr( $sort, 1 ) . ' DESC ' : $sort . ' ASC ' );
        }
        // Default
        else {
            $sortQuery = 'c2 ASC';
            $sort = 'c2';
        }

        // TBD Filter

        // Pagination
        $pageSize    = DEFAULT_PAGE_SIZE;
        $currentPage = Url_LIB::getRequestParam('p') ? Url_LIB::getRequestParam('p') : 1;
        $startingRow = ( $currentPage - 1 ) * $pageSize;

        // First request data of the current page
        // TBD: push into sqldriver/base model for reuse
        $query = 'SELECT SQL_CALC_FOUND_ROWS '
                . '     u.id        c1, '
                . '     u.username  c2, '
                . '     u.email     c3, '
                . '     r.label     c4 '
                . 'FROM '
                . '     users u '
                . '     INNER JOIN roles r ON '
                . '         r.id = u.id_role '
                . "ORDER BY $sortQuery "
                . "LIMIT $startingRow, $pageSize ";

        $this->query( $query );
        $data = $this->fetchAll('array');

        // Next, retrieve the number of total result
        $this->query( 'SELECT FOUND_ROWS();' );
        $result = $this->fetchNext('array');
        $resultNumber = $result['FOUND_ROWS()'];

        // Total number of pages
        $pageNumber = ceil( $resultNumber / $pageSize );

        // Set data ready to retrieve
        return array( $data, $currentPage, $pageNumber, $sort );
    }
}
