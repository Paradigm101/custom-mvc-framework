<?php

// A base model to implement boards
class Board_LIB_Model extends Base_LIB_Model {
    
    // Custom method: default sort
    protected function getBoardDefaultSort() {
        
        Log_LIB::trace('[Board_LIB_Model] Method ' . __METHOD__ . ' has to be overwritten from [' . get_called_class() . ']');
    }

    // Custom method: query
    protected function getBoardQuery() {
        
        Log_LIB::trace('[Board_LIB_Model] Method ' . __METHOD__ . ' has to be overwritten from [' . get_called_class() . ']');
    }

    private $initDone = false;
    
    // Sort (with default)
    private $boardSort;

    public function getBoardSort() {

        if ( !$this->initDone ) {
            $this->computeBoardData();
            $this->initDone = true;
        }
        
        return $this->boardSort;
    }

    // Data in DB
    private $boardData;
    
    public function getBoardData() {
        
        if ( !$this->initDone ) {
        
            $this->computeBoardData();
            $this->initDone = true;
        }
        
        return $this->boardData;
    }

    // Current page #
    private $boardCurrentPage;
    
    public function getBoardCurrentPage() {
        
        if ( !$this->initDone ) {
            $this->computeBoardData();
            $this->initDone = true;
        }
        
        return $this->boardCurrentPage;
    }

    // Total number of pages
    private $boardPageNumber;
    
    public function getBoardPageNumber() {
        
        if ( !$this->initDone ) {
            $this->computeBoardData();
            $this->initDone = true;
        }
        
        return $this->boardPageNumber;
    }

    // Filters
    private $boardFilters;
    
    public function getBoardFilters() {
        
        if ( !$this->initDone ) {
            $this->computeBoardData();
            $this->initDone = true;
        }
        
        return $this->boardFilters;
    }
    
    // Page size
    private $boardPageSize = DEFAULT_PAGE_SIZE;
    
    public function setBoardPageSize( $pageSize ) {
        $this->boardPageSize = $pageSize;
    }
    
    // Retrieve data and store
    private function computeBoardData() {

        // Retrieve sort from URL and default sorting
        if ( !($sort = $this->getStringForQuery( Url_LIB::getRequestParam('s') ) ) ) {

            $sort = $this->getBoardDefaultSort();
        }

        // Prepare sort for query
        $sortQuery = ' ORDER BY ' . ( $sort[0] == '_' ? substr( $sort, 1 ) . ' DESC ' : $sort . ' ASC ' ) . ' ';

        // Pagination (with protection)
        $currentPage = ( Url_LIB::getRequestParam('p') ? ( 0 + Url_LIB::getRequestParam('p') ) : 1 );
        if ( $currentPage < 1 ) {
            $currentPage = 1;
        }
        
        $limitQuery = ' LIMIT ' . ( $currentPage - 1 ) * $this->boardPageSize . ', ' . $this->boardPageSize . ' ';

        // First request data of the current page
        $query = 'SELECT SQL_CALC_FOUND_ROWS ' . substr($this->getBoardQuery(), 7) . ' '
                . $sortQuery . ' '
                . $limitQuery;

        $this->query( $query );
        $data = $this->fetchAll('array');

        // Next, retrieve the number of total rows (for pagination)
        $this->query( 'SELECT FOUND_ROWS();' );
        $result = $this->fetchNext('array');
        $resultNumber = $result['FOUND_ROWS()'];

        // Store data for use
        $this->boardSort        = $sort;
        $this->boardData        = $data;
        $this->boardCurrentPage = $currentPage;
        $this->boardPageNumber  = max( ceil( $resultNumber / $this->boardPageSize ), 1 );
        $this->boardFilters     = Url_LIB::getBoardFilter();
    }
}
