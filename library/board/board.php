<?php

// Generic class to manage table/board display
class Board_LIB {

    // Data to display in board
    private $data = array();

    public function setData( $data ) {
        $this->data = $data;
        return $this;
    }
    
    // Pagination: current page
    private $currentPage = null;
    
    public function setCurrentPage( $currentPage ) {
        $this->currentPage = $currentPage;
        return $this;
    }
    
    // Pagination: number of page
    private $pageNumber = null;
    
    public function setPageNumber( $pageNumber ) {
        $this->pageNumber = $pageNumber;
        return $this;
    }
    
    // Sort: column (start with '_' for reverse)
    private $sort = null;
    
    public function setSort( $sort ) {
        $this->sort = $sort;
        return $this;
    }
    
    // Calling page (or ajax or api) for sort/pagination reload
    private $requestName;
    
    public function setRequestName( $requestName ) {
        $this->requestName = $requestName;
        return $this;
    }
    
    // In case no data, message to display user
    private $noDataMessage = 'No data';

    public function setNoDataMessage( $message ) {
        $this->noDataMessage = $message;
        return $this;
    }

    // For interface, data description
    private $metadata;
    
    public function setMetadata( $metadataFile ) {
        
        // Manage No metadata file
        if ( !$metadataFile ) {

            // Get call stack
            $backTrace = debug_backtrace();

            // Log error for dev
            Log_LIB::trace('[Board_LIB] No metadata file for class [' . $backTrace[1]['class'] . ']');

            return;
        }

        // Get config file for pages
        $csvFile = fopen( $metadataFile, 'r' );

        // Parsing file and storing data
        while ( $line = fgetcsv( $csvFile ) ) {

            // Add metadata
            $metadata[$line[0]] = array( 'type'            => trim( $line[1] ),
                                         'is_shown'        => trim( $line[2] ) ? true : false,
                                         'label'           => trim( $line[3] ),
                                         'is_filtered'     => trim( $line[4] ) ? true : false,
                                         'is_sortable'     => trim( $line[5] ) ? true : false,
                                         'column_size'     => trim( $line[6] ) );
        }

        $this->metadata = $metadata;
        return $this;
    }

    private function isValid() {
        
        // Check for mandatory data
        if ( !$this->metadata    ||
             !$this->currentPage ||
             !$this->pageNumber  ||
             !$this->sort        ||
             !$this->requestName ) {

            return false;
        }

        // Check alignment (data can be empty though)
        if ( count($this->data) ) {

            // Comparing keys of metadata to keys of the first raw of data (order and values)
            if ( array_keys($this->data[0]) != array_keys($this->metadata) ) {

                // Get call stack
                $backTrace = debug_backtrace();

                // Log error for dev
                Log_LIB::trace('[Board_LIB] Metadata/Data no aligned for [' . $backTrace[1]['class'] . ']');

                return false;
            }
        }

        // Yay, it's valid!
        return true;
    }

    // Javascript to manage filter, sort and pagination
    private function getBoardScript() {
        
        return "var board_page = {$this->currentPage};\n"
                . "var board_sort = '{$this->sort}';\n"
                . "\n"
                . "var board_reload = function() {\n"
                .     "\n"
                .     "window.location.href = getURL('{$this->requestName}') + '&s=' + board_sort + ( board_page != 1 ? '&p=' + board_page : '' );\n"
                . "}\n"
                . "\n"
                . "var board_sort_reload = function(sort) {\n"
                .     "\n"
                .     "board_sort = sort;\n"
                .     "\n"
                .     "board_reload();\n"
                .     "\n"
                . "}\n"
                . "\n"
                . "var board_page_reload = function(page) {\n"
                .     "\n"
                .     "switch ( page ) {\n"
                .         "case 'first':\n"
                .             "board_page = 1;\n"
                .             "break;\n"
                .         "case 'previous':\n"
                .             "board_page--;\n"
                .             "if ( board_page <= 0 ) board_page = 1;"
                .             "break;\n"
                .         "case 'next':\n"
                .             "board_page++;\n"
                .             "if ( board_page > {$this->pageNumber} ) board_page = {$this->pageNumber};"
                .             "break;\n"
                .         "case 'last':\n"
                .             "board_page = {$this->pageNumber};\n"
                .             "break;\n"
                .     "}\n"
                .     "\n"
                .     "board_reload();\n"
                .     "\n"
                . "}\n";
    }

    // Display board (for template)
    // TBD: fixed column size
    // TBD: put pagination buttons at bottom of the page for incomplete board
    // TBD: filters
    public function display() {

        // Check validity first: data/metadata alignment, etc...
        if ( !$this->isValid() ) {

            return 'Internal error';
        }

        // Add Javascript to manage this nice board
        // will be added at the end of the page, after template displays
        Page_LIB::addJavascript($this->getBoardScript());

        // Start table
        $toDisplay = "<table class=\"table table-hover table-bordered table-condensed table-striped\">\n";

        // Start header
        $toDisplay .= "<thead>\n"
                        . "<tr>\n"
                            // Checkbox
                            . '<th style="width:25px;"></th>' . "\n";

        // Display each field title
        foreach( $this->metadata as $key => $param ) {

            // Check if this field is displayed
            if ( $param['is_shown'] ) {

                // If this is NOT a sortable field
                if ( !$param['is_sortable'] ) {

                    // Display it with the caret and link to inverse sort
                    $toDisplay .= '<th>' . ucfirst( $param['label'] ) . '</th>' . "\n";
                }
                // If this is the sorted field
                else if ( $this->sort == $key ) {

                    // Display it with the caret and link to inverse sort
                    $toDisplay .= '<th title="Click to reverse the sorting">'
                                    . '<a onclick="board_sort_reload(\'_' . $key . '\');">'
                                        . ucfirst( $param['label'] )
                                    . '</a>'
                                    . '<span class="caret"></span>'
                                . '</th>' . "\n";
                }
                // If this is the reverse sorted field
                else if ( $this->sort == '_' . $key ) {

                    // Display it with the inversed caret and link to regular sort
                    $toDisplay .= '<th title="Click to reverse the sorting">'
                                    . '<a onclick="board_sort_reload(\'' . $key . '\');">'
                                        . ucfirst( $param['label'] )
                                    . '</a>'
                                    . '<span class="dropup"><span class="caret"></span></span>'
                                . '</th>' . "\n";
                }
                // Not the sorted field
                else {

                    // Display it with NO caret and link to regular sort
                    $toDisplay .= '<th title="Click to sort by ' . ucfirst( $param['label'] ) . '">'
                                    . '<a onclick="board_sort_reload(\'' . $key . '\');">'
                                        . ucfirst( $param['label'] )
                                    . '</a>'
                                . '</th>' . "\n";
                }
            }
        }

        // End header
        $toDisplay .= "</tr>\n"
                . "</thead>\n";

        /***********************************************************************************/
        
        // Start body
        $toDisplay .= "<tbody>\n";

        // With data
        if ( count( $this->data ) ) {
            
            // Display all rows
            foreach( $this->data as $id => $row ) {

                // Start with the checkbox
                $toDisplay .= "<tr>\n"
                                . '<td><input type="checkbox" id="checkbox_' . $id . '"></td>' . "\n";
                
                // Display each fields
                foreach( $row as $name => $value ) {
       
                    // Check if this field is displayed
                    if ( $this->metadata[$name]['is_shown'] ) {

                        $toDisplay .= "<td>$value</td>\n";
                    }
                }

                // End row
                $toDisplay .= "</tr>\n";
            }
        }
        // Case no data
        else {
            
            // Colspan size, at lease 1 for checkbox
            $colspan = 1;
            
            // Check is shown
            foreach ( $this->metadata as $param ) {

                if ( $param['is_shown'] ) {

                    $colspan++;
                }
            }
            
            $toDisplay .= "<tr><td colspan=\"$colspan\">{$this->noDataMessage}</td></tr>\n";
        }

        // End body
        $toDisplay .= "</tbody>\n";

        // End table
        $toDisplay .= "</table>\n";

        // Start of pagination
        $toDisplay .= '<div align="right">';
        
        // Button states
        if ( $this->currentPage > 1 ) {
            $previousState = '';
        }
        else {
            $previousState = 'disabled';
        }
        if ( $this->currentPage < $this->pageNumber ) {
            $nextState = '';
        }
        else {
            $nextState = 'disabled';
        }

        // Display
        $toDisplay .= '<button class="btn btn-default glyphicon glyphicon-backward ' . $previousState . '"     onclick="board_page_reload(\'first\')"></button>'
                   .  '<button class="btn btn-default glyphicon glyphicon-chevron-left ' . $previousState . '" onclick="board_page_reload(\'previous\')"></button>'
                   .  '<span style="margin:10px;">Page ' . $this->currentPage . ' / ' . $this->pageNumber . '</span>'
                   .  '<button class="btn btn-default glyphicon glyphicon-chevron-right ' . $nextState . '"    onclick="board_page_reload(\'next\')"></button>'
                   .  '<button class="btn btn-default glyphicon glyphicon-forward ' . $nextState . '"          onclick="board_page_reload(\'last\')"></button>';
        
        // End of pagination
        $toDisplay .= '</div>';

        return $toDisplay;
    }
}
