<?php

// Generic class to manage table/board display
class Board_LIB {

    // Data to display
    private $data;

    // Total number of result for pagination
    private $resultNumber;

    // Pagination
    private $currentPage;
    private $pageNumber;
    
    // The guy who called
    private $requestName;
    
    // For interface
    private $metadata;

    // In case no data
    private $noDataMessage;

    // Private constructor : use factory
    public function __construct( $data, $resultNumber, $currentPage, $pageNumber, $requestName, $metadataFile, $noDataMessage = 'No data' ) {

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
                                         'is_sortable' => trim( $line[5] ) ? true : false );
        }

        // Check alignment
        if ( $data ) {

            // Comparing keys of metadata to keys of the first raw of data (order and values)
            if ( array_keys($data[0]) != array_keys($metadata) ) {

                // Get call stack
                $backTrace = debug_backtrace();

                // Log error for dev
                Log_LIB::trace('[Board_LIB] Metadata/Data no aligned for [' . $backTrace[1]['class'] . ']');

                return;
            }
        }

        $this->data          = $data;
        $this->resultNumber  = $resultNumber;
        $this->currentPage   = $currentPage;
        $this->pageNumber    = $pageNumber;
        $this->requestName   = $requestName;
        $this->metadata      = $metadata;
        $this->noDataMessage = $noDataMessage;
    }

    // Display board (for template)
    public function display() {

        // If no metadata, no need to continue
        if ( !$this->metadata ) {

            return 'Internal error';
        }
        
        // Start table
        $toDisplay = "<table class=\"table table-hover table-bordered table-condensed table-striped\">\n";

        // Start header
        $toDisplay .= "<thead>\n"
                        . "<tr>\n"
                            // Checkbox
                            . '<th></th>' . "\n";

        // Display each field title
        foreach( $this->metadata as $param ) {

            // Check if this field is displayed
            if ( $param['is_shown'] ) {
                
                $toDisplay .= "<th>" . ucfirst( $param['label'] ) . "</th>\n";
            }
        }

        // End header
        $toDisplay .= "</tr>\n"
                . "</thead>\n";

        // Start body
        $toDisplay .= "<tbody>\n";

        // With data
        if ( $this->data ) {
            
            // Display all rows
            foreach( $this->data as $id => $row ) {

                // Start with the checkbox
                $toDisplay .= "<tr>\n"
                                . '<td style="width:20px;"><input type="checkbox" id="checkbox_' . $id . '"></td>' . "\n";

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

        // Display buttons for pagination
        $toDisplay .= '<div align="right">'
                        . '<button class="btn btn-default" onclick="board_reload(\'first\')"><<</button>'
                        . '<button class="btn btn-default" onclick="board_reload(\'previous\')"><</button>'
                        . '<button class="btn btn-default" onclick="board_reload(\'next\')">></button>'
                        . '<button class="btn btn-default" onclick="board_reload(\'last\')">>></button>'
                    . '</div>';

        // Javascript to manage filter, sort and pagination
        $script = "\n"
                . "var board_page = {$this->currentPage};\n"
                . "\n"
                . "var board_reload = function(page) {\n"
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
                .     "window.location.href = getURL('{$this->requestName}') + '&p=' + board_page;\n"
                .     "\n"
                . "}\n";
        Page_LIB::addJavascript($script);

        return $toDisplay;
    }
}
