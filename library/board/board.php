<?php

// Generic class to manage table/board display
class Board_LIB extends Base_LIB_Model {

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

    // Filters
    private $filters = null;

    public function setFilters( $filters ) {
        $this->filters = $filters;
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
            $metadata[$line[0]] = array( 'type'        => trim( $line[1] ),
                                         'is_shown'    => trim( $line[2] ) ? true : false,
                                         'label'       => trim( $line[3] ),
                                         'is_filtered' => trim( $line[4] ) ? true : false,
                                         'is_sortable' => trim( $line[5] ) ? true : false,
                                         'column_size' => trim( $line[6] ) + 0 );
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

        // Yay, that's valid!
        return true;
    }

    // Javascript to manage filter, sort and pagination
    private function getBoardScript() {

        return <<<EOD

        // Page variables
        var board_page_{$this->requestName} = {$this->currentPage};
        var board_sort_{$this->requestName} = '{$this->sort}';

        // Reload page
        var board_reload_{$this->requestName} = function() {

            var sort_url = '&s=' + board_sort_{$this->requestName};
            var page_url = ( board_page_{$this->requestName} != 1 ? '&p=' + board_page_{$this->requestName} : '' );

            var filter_url = '';
            $('input[name=board_filter_{$this->requestName}]').each(function() {
                if ( $(this).val() ) {
                    filter_url += '&f_' + $(this).attr('id') + '=' + $(this).val();
                }
            });

            window.location.href = getURL('{$this->requestName}') + sort_url + page_url + filter_url;
        }

        // When sorting column title clicked
        var board_sort_reload_{$this->requestName} = function(sort) {

            board_sort_{$this->requestName} = sort;
            board_reload_{$this->requestName}();
        }

        // When pagination button clicked
        var board_page_reload_{$this->requestName} = function(page) {
        
            switch ( page ) {
                case 'first':
                    board_page_{$this->requestName} = 1;
                    break;

                case 'previous':
                    board_page_{$this->requestName}--;
                    if ( board_page_{$this->requestName} <= 0 ) {
                        board_page_{$this->requestName} = 1;
                    }
                    break;

                case 'next':
                    board_page_{$this->requestName}++;
                    if ( board_page_{$this->requestName} > {$this->pageNumber} ) {
                        board_page_{$this->requestName} = {$this->pageNumber};
                    }
                    break;

                case 'last':
                    board_page_{$this->requestName} = {$this->pageNumber};
                    break;
                }

            board_reload_{$this->requestName}();
        };

        $('input[name=cb_board_{$this->requestName}]').click( function() {
        
            console.log('this.checked : ' + this.checked);
            console.log('$(this).attr(\'id\') : ' + $(this).attr('id'));
        });
EOD;
    }

    // Display board (for template)
    // TBD: fixed column size
    // TBD: put pagination buttons at bottom of the page for incomplete board
    public function display() {

        // Check validity first: data/metadata alignment, etc...
        if ( !$this->isValid() ) {

            return 'Internal error';
        }

        // Add Javascript to manage this nice board
        // will be added at the end of the page, after template displays
        Page_LIB::addJavascript($this->getBoardScript());

        // Create a temporary table for this specific user and session and board
        //      - to store checked items
        //      - to allow batch commands
        //      - user has to be logged in
        if ( Session_LIB::isUserLoggedIn() ) {

            // Create table only the first time the user arrives on this page
            $query = 'CREATE TABLE IF NOT EXISTS `TMP_Board_' . $this->requestName . '_' . Session_LIB::getSessionId() . '` ('
                    . '`id` INT NOT NULL AUTO_INCREMENT, '
                    . 'PRIMARY KEY (`id`), '
                    . 'UNIQUE KEY `id` (`id`) ) '
                    . 'ENGINE=InnoDB DEFAULT CHARSET=latin1;';

            $this->query($query);
        }

        /******************************** TABLE HEAD ******************************************/
        
        // Start table
        $toDisplay = "<table class=\"table table-hover table-bordered table-condensed table-striped\">\n";

        // Start header
        $toDisplay .= "<thead>\n"
                        . "<tr>\n"
                            // Checkbox (only for logged-in users
                            . ( Session_LIB::isUserLoggedIn() ? '<th style="width:20px;"></th>' . "\n" : '' );

        // Display each field title
        foreach( $this->metadata as $key => $param ) {

            // Check if this field is displayed
            if ( $param['is_shown'] ) {

                // Manage column size
                $size = '';
                if ( $param['column_size'] ) {

                    $size = "width:{$param['column_size']}px;";
                }

                // Label to display
                $label = ucfirst( $param['label'] ) . "\n";
                
                // Tooltip
                $title = '';
                
                // Sortable column: add stuff
                if ( $param['is_sortable'] ) {

                    // Sorted column
                    if ( $this->sort == $key ) {

                        // Reverse sort on click
                        $sortKey = '_' . $key;
                        
                        // Symbol displayed : caret
                        $symbol = '<span class="caret"></span>';
                        
                        // Tooltip
                        $title = 'Click to reverse the sorting';
                    }
                    // Reverse sorted column
                    else if ( $this->sort == '_' . $key ) {

                        // Sort on click
                        $sortKey = $key;
                        
                        // Symbol displayed : reverse caret
                        $symbol = '<span class="dropup"><span class="caret"></span></span>';
                
                        // Tooltip
                        $title = 'Click to reverse the sorting';
                    }
                    // NOT sorted column (yet)
                    else {

                        // Sort on click
                        $sortKey = $key;

                        // Symbol displayed : none
                        $symbol = '';

                        // Tooltip
                        $title = 'Click to sort by ' . ucfirst( $param['label'] );
                    }
                    
                    $startAnchor = "<a onclick=\"board_sort_reload_{$this->requestName}('$sortKey');\">\n";
                    $endAnchor   = "</a>\n";
                    
                    $label = $startAnchor . $label . $endAnchor . $symbol . "\n";
                }

                // Filtered column, add other stuff
                $filter = '';
                if ( $param['is_filtered'] ) {
                    
                    $value = '';
                    foreach ( $this->filters as $filterName => $filterValue ) {
                        if ( $filterName == $key ) {
                            $value = $filterValue;
                        }
                    }
                    
                    $filter = "<br/><input id=\"$key\" "
                            . "name=\"board_filter_{$this->requestName}\" "
                            . "onkeydown=\"if (event.which===13) board_reload_{$this->requestName}();\" "
                            . "title=\"Filter " . ucfirst( $param['label'] ) . "\" value=\"$value\" />";
                }

                $toDisplay .= "<th style=\"$size;vertical-align:top;text-align:center;\">\n"
                                . '<span title="' . $title . '">' . $label . '</span>'
                                . "$filter"
                            . "</th>\n";
            }
        }

        // End header
        $toDisplay .= "</tr>\n"
                . "</thead>\n";

        /********************************** TABLE BODY *****************************************/
        
        // Start body
        $toDisplay .= "<tbody>\n";

        // With data
        if ( count( $this->data ) ) {
            
            // Display all rows
            foreach( $this->data as $id => $row ) {

                // Start row
                $toDisplay .= "<tr>\n";

                // Checkbox for logged-in user
                if ( Session_LIB::isUserLoggedIn() ) {

                    $toDisplay .= '<td title="Click to select/unselect this item">'
                                    . '<input type="checkbox" name="cb_board_' . $this->requestName . '" id="' . $id . '">'
                                . '</td>' . "\n";
                }

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

        /********************************** FOOTER : PAGINATION ************************************/

        // Start of pagination
        $toDisplay .= '<div style="float: right;">';

        // Manage back buttons
        $previousState = '';
        if ( $this->currentPage == 1 ) {
            $previousState = 'disabled';
        }

        // Manage next buttons
        $nextState = '';
        if ( $this->currentPage >= $this->pageNumber ) {
            $nextState = 'disabled';
        }

        // Display
        $toDisplay .= '<button class="btn btn-default glyphicon glyphicon-backward ' . $previousState . '"     onclick="board_page_reload_' . $this->requestName . '(\'first\')"></button>'
                   .  '<button class="btn btn-default glyphicon glyphicon-chevron-left ' . $previousState . '" onclick="board_page_reload_' . $this->requestName . '(\'previous\')"></button>'
                   .  '<span style="margin:10px;">Page ' . $this->currentPage . ' / ' . $this->pageNumber . '</span>'
                   .  '<button class="btn btn-default glyphicon glyphicon-chevron-right ' . $nextState . '"    onclick="board_page_reload_' . $this->requestName . '(\'next\')"></button>'
                   .  '<button class="btn btn-default glyphicon glyphicon-forward ' . $nextState . '"          onclick="board_page_reload_' . $this->requestName . '(\'last\')"></button>';
        
        // End of pagination
        $toDisplay .= '</div>';

        // Eventually return the whole thing for display
        return $toDisplay;
    }
}
