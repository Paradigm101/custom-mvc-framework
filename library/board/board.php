<?php

// Generic class to manage table/board display
// TBD: when changing sort or filter, reset tmp table
class Board_LIB {

    // Internal attributes
    private $model;                 // For database access
    private $requestName;           // Page name (or ajax/api name)
    private $noDataMessage;         // In case no data, message to display user
    private $primaryId = null;      // Store primary board id
    private $metadata;              // For interface, data description
    private $data = array();        // Data to display in board
    private $currentPage = null;    // Pagination: current page
    private $pageNumber = null;     // Pagination: number of page
    private $sort = null;           // Sort: column (start with '_' for reverse)
    private $filters = null;        // Filters
    private $selectedIds = array(); // Selected items
    private $actions = array();     // Page actions

    // One entry point for board temporay table name
    private function getTemporaryTableName() {

        return 'TMP_Board_' . $this->requestName . '_' . Session_LIB::getSession();
    }

    // TBD: manage bad construction (missing data, etc...)
    public function __construct( $requestName,
                                 $metadataFile,
                                 $actionFile,
                                 $query,
                                 $sort,
                                 $noDataMsg = 'No data' ) {

        // Very important: do first!
        $this->requestName   = $requestName;
        $this->noDataMessage = $noDataMsg;

        /****************************************** METADATA *********************************************************/
        // Retrieve metadata and primary id
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

            // Set primary id (for batch actions)
            if ( $line[7] ) {
                $this->primaryId = $line[0];
            }
        }

        $this->metadata = $metadata;
        
        /****************************************** METADATA *********************************************************/
        // Retrieve actions if any
        if ( $actionFile ) {
            
            // Get config file for pages
            $csvFile = fopen( $actionFile, 'r' );

            // Parsing file and storing data
            while ( $line = fgetcsv( $csvFile ) ) {

                // Add metadata
                $this->actions[] = array( 'name'         => trim( $line[0] ),
                                          'javascriptCB' => trim( $line[1] ),
                                          'icon'         => trim( $line[2] ),
                                          'is_single'    => trim( $line[3] ) ? true : false,
                                          'is_batch'     => trim( $line[4] ) ? true : false,
                                          'is_all'       => trim( $line[5] ) ? true : false );
            }
        }

        /****************************************** MODEL *********************************************************/
        $this->model = new Board_LIB_Model( $query,
                                            $sort,
                                            $this->getTemporaryTableName(),
                                            $this->primaryId,
                                            DEFAULT_PAGE_SIZE );
        
        // Get information from model
        $this->data        = $this->model->getBoardData();
        $this->currentPage = $this->model->getBoardCurrentPage();
        $this->pageNumber  = $this->model->getBoardPageNumber();
        $this->sort        = $this->model->getBoardSort();
        $this->filters     = $this->model->getBoardFilters();
        $this->selectedIds = $this->model->getBoardSelected();

        /****************************************** ALIGNMENT *********************************************************/
        // Check alignment (data can be empty though)
        if ( count($this->data) ) {

            // Comparing keys of metadata to keys of the first raw of data (order and values)
            if ( array_keys($this->data[0]) != array_keys($this->metadata) ) {

                // Get call stack
                $backTrace = debug_backtrace();

                // Log error for dev
                Log_LIB::trace('[Board_LIB] Metadata/Data no aligned for [' . $backTrace[1]['class'] . ']');
            }
        }

        /****************************************** SCRIPT *********************************************************/
        // Add Javascript to manage this nice board
        // will be added at the end of the page, after template displays
        Page_LIB::addJavascript( $this->getBoardScript() );
        
        // Need URL_LIB javascript
        Page_LIB::subscribeClassForJavascript('Url_LIB');
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

            window.location.href = getURL('{$this->requestName}') + '?' + sort_url + page_url + filter_url;
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

        // Store checkbox modifications in temporary table
        $('input:checkbox[name=board_{$this->requestName}_cb_select]').click( function() {

            // Launch ajax
            $.ajax({
                type: "POST",
                url: "",
                global: false,
                data: {
                    rt:         REQUEST_TYPE_AJAX,      // request type
                    rn:         'cb_change',            // request name
                    cb_id:      $(this).attr('id'),
                    is_checked: this.checked ? 1 : 0,
                    table_name: '{$this->getTemporaryTableName()}'
                }
            });
        });

        // Page checkbox: check/uncheck all checkboxes
        $('input:checkbox[name=board_{$this->requestName}_cb_select_all]').click( function() {

            // If global selector is checked: click every unchecked item checkbox selector
            if ( this.checked ) {
                $('input:checkbox:not(:checked)[name=board_{$this->requestName}_cb_select]').click();
            }
            // Else, global selector is NOT checked: click every checked item checkbox selector
            else {
                $('input:checkbox:checked[name=board_{$this->requestName}_cb_select]').click();
            }
        });
EOD;
    }

    // Display board (for template)
    // TBD: fixed column size
    // TBD: put pagination buttons at bottom of the page for incomplete board
    public function display() {

        // By default no global action display and no action column
        $isGlobalActions = false;
        $isSingleActions = false;

        // User needs to be logged in to have access to actions
        if ( Session_LIB::isUserLoggedIn() ) {

            // Is there any global/single action in the list?
            foreach ( $this->actions as $action ) {
                
                $isGlobalActions = $isGlobalActions || $action['is_batch'] || $action['is_all'];
                $isSingleActions = $isSingleActions || $action['is_single'];
            }
        }

        /******************************** TABLE HEAD ******************************************/
        // Start table
        $toDisplay = "<table class=\"table table-hover table-bordered table-condensed table-striped\">\n";

        // Start header
        $toDisplay .= "<thead>\n"
                        . "<tr>\n";

        // Global checkbox: select/unselect on every row in the page
        if ( $isGlobalActions ) {

            $toDisplay .= '<th title="Click to select/unselect all items in this page" style="width:20px;">'
                            . '<input type="checkbox" '
                                   . 'name="board_' . $this->requestName . '_cb_select_all" />'
                        . '</th>' . "\n";
        }

        // Action column
        if ( $isSingleActions )
        {
            $toDisplay .= "<th  style=\"vertical-align:top;text-align:center;\">Actions</th>\n";
        }
        
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

                // Checkbox for specific row
                if ( $isGlobalActions )
                {
                    $toDisplay .= '<td title="Click to select/unselect this item">'
                                    . '<input type="checkbox" '
                                            . 'name="board_' . $this->requestName . '_cb_select" '
                                            . 'id="' . $row[$this->primaryId] . '"'
                                            . ( in_array( $row[$this->primaryId], $this->selectedIds ) ? 'checked="checked"' : '' ) . ' />'
                                . '</td>' . "\n";
                }

                // Single action column
                if ( $isSingleActions )
                {
                    $toDisplay .= '<td>';
                    
                    foreach ( $this->actions as $action )
                    {
                        if ( $action['is_single'] )
                        {
                            $toDisplay .= '<a onclick="' . $action['javascriptCB'] . '(' . $row[$this->primaryId] . ');">'
                                            . '<span class="glyphicon glyphicon-' . $action['icon'] . '"></span>'
                                        . '</a>';
                        }
                    }

                    $toDisplay .= "</td>\n";
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

        /********************************** FOOTER : GLOBAL ACTIONS & PAGINATION ************************************/

        // Global actions
        //---------------
        if ( $isGlobalActions ) {

            // Add dropup menu
            $toDisplay .= '<div class="btn-group dropup" style="float: left;">'
                            . '<button class="btn btn-default" type="button">Global actions</button>'
                            . '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                                . '<span class="caret"></span>'
                                . '<span class="sr-only">Toggle Dropdown</span>'
                            . '</button>'
                            . '<ul class="dropdown-menu" role="menu">';

            // Preparing list items
            $batchActions  = '';
            $separator     = '';
            $globalActions = '';

            // Add actions according to their type
            foreach ( $this->actions as $action ) {

                // Batch
                if ( $action['is_batch'] ) {
                    
                    $batchActions .= '<li role="presentation">'
                                        . '<a role="menuitem" onclick="' . $action['javascriptCB'] . '( \'' . $this->getTemporaryTableName() . '\' );">'
                                            . ucfirst( $action['name'] ) . ' selected items'
                                        . '</a>'
                                    . '</li>' . "\n";
                }
                // Global
                else if ( $action['is_all'] ) {

                    $globalActions .= '<li role="presentation">'
                                        . '<a role="menuitem" onclick="' . $action['javascriptCB'] . '( \'' . $this->getTemporaryTableName() . '\' );">'
                                            . ucfirst( $action['name'] ) . ' all items in this table'
                                        . '</a>'
                                    . '</li>' . "\n";
                }
            }

            // Action seperator if needed
            if ( $batchActions || $globalActions ) {
                $separator = '<li role="presentation" class="divider"></li>';
            }

            $toDisplay .= $batchActions
                        . $separator
                        . $globalActions;

            $toDisplay .= '</ul>'
                    . '</div>';
        }

        // Pagination
        //-----------
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
        $toDisplay .= '<button class="btn btn-default glyphicon glyphicon-backward ' . $previousState . '"'
                            . 'onclick="board_page_reload_' . $this->requestName . '(\'first\')"></button>'
                   .  '<button class="btn btn-default glyphicon glyphicon-chevron-left ' . $previousState . '"'
                            . ' onclick="board_page_reload_' . $this->requestName . '(\'previous\')"></button>'
                   .  '<span style="margin:10px;">Page ' . $this->currentPage . ' / ' . $this->pageNumber . '</span>'
                   .  '<button class="btn btn-default glyphicon glyphicon-chevron-right ' . $nextState . '"'
                            . 'onclick="board_page_reload_' . $this->requestName . '(\'next\')"></button>'
                   .  '<button class="btn btn-default glyphicon glyphicon-forward ' . $nextState . '"'
                            . 'onclick="board_page_reload_' . $this->requestName . '(\'last\')"></button>';
        
        // End of pagination
        $toDisplay .= '</div>';

        // Eventually return the whole thing for display
        return $toDisplay;
    }
}
