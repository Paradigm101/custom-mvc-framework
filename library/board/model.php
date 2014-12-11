<?php

class Board_LIB_Model extends Base_LIB_Model {

    // Sort (with default)
    private $boardSort;

    public function getBoardSort() {

        return $this->boardSort;
    }

    // Data in DB
    private $boardData;
    
    public function getBoardData() {
        
        return $this->boardData;
    }

    // Current page #
    private $boardCurrentPage;
    
    public function getBoardCurrentPage() {
        
        return $this->boardCurrentPage;
    }

    // Total number of pages
    private $boardPageNumber;
    
    public function getBoardPageNumber() {
        
        return $this->boardPageNumber;
    }

    // Filters
    private $boardFilters;
    
    public function getBoardFilters() {
        
        return $this->boardFilters;
    }
    
    // Selected checkboxes
    private $boardSelected;
    
    public function getBoardSelected() {
        
        return $this->boardSelected;
    }
    
    // Compute everything during creation
    public function __construct( $boardQuery,
                                 $defaultSort,
                                 $temporaryTableName,
                                 $primaryId,
                                 $pageSize = DEFAULT_PAGE_SIZE ) {

        // Do parent and then some interesting stuff...
        parent::__construct();

        // Retrieve sort from URL or default sorting
        if ( !($sort = $this->getStringForQuery( Url_LIB::getRequestParam('s') ) ) ) {

            $sort = $defaultSort;
        }

        // Prepare sort for query
        $sortQuery = ' ORDER BY ' . ( $sort[0] == '_' ? substr( $sort, 1 ) . ' DESC ' : $sort . ' ASC ' ) . ' ';

        // Pagination (with protection)
        $currentPage = ( Url_LIB::getRequestParam('p') ? ( 0 + Url_LIB::getRequestParam('p') ) : 1 );
        if ( $currentPage < 1 ) {
            $currentPage = 1;
        }
        
        $limitQuery = ' LIMIT ' . ( $currentPage - 1 ) * $pageSize . ', ' . $pageSize . ' ';

        // First request data of the current page
        $query = 'SELECT SQL_CALC_FOUND_ROWS ' . substr($boardQuery, 7) . ' '
                . $sortQuery . ' '
                . $limitQuery;

        $this->query( $query );
        $data = $this->fetchAll('array');

        // Next, retrieve the number of total rows (for pagination)
        $this->query( 'SELECT FOUND_ROWS();' );
        $result = $this->fetchNext('array');
        $resultNumber = $result['FOUND_ROWS()'];

        // Manage user session for this board
        $ids = array();
        if ( Session_LIB::isUserLoggedIn() ) {

            /************************************* TEMPORARY TABLE ************************************************/
            $this->query("CREATE TABLE IF NOT EXISTS `$temporaryTableName` ("
                        . '`id_item` VARCHAR(255) NOT NULL, '
                        . 'UNIQUE KEY `id_item` (`id_item`) ) '
                        . 'ENGINE=InnoDB DEFAULT CHARSET=latin1;');

            /************************************* SELECTED CHECKBOX ************************************************/
            // Retrieve this page item's IDs
            $ids = '';
            foreach ($data as $row) {
                $ids .= "'cb_sel_{$row[$primaryId]}', ";
            }
            $ids = substr($ids, 0, -2);

            // Retrive checked checkbox selector
            $query = "SELECT id_item FROM `$temporaryTableName` WHERE id_item IN ($ids)";

            $this->query($query);

            $ids = array();
            foreach( $this->fetchAll() as $item ) {
                $ids[] = $item->id_item;
            }
        }

        /************************************* STORE DATA ************************************************/
        $this->boardSort        = $sort;
        $this->boardData        = $data;
        $this->boardCurrentPage = $currentPage;
        $this->boardPageNumber  = max( ceil( $resultNumber / $pageSize ), 1 );
        $this->boardFilters     = Url_LIB::getBoardFilter();
        $this->boardSelected    = $ids;
    }
}
