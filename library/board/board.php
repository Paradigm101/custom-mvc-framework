<?php

// Generic class to manage table/board display
class Board_LIB {

    // Data to display
    private $data;

    // For interface
    private $metadata;

    // In case no data
    private $noDataMessage;
    
    // Private constructor : use factory
    public function __construct( $data, $metadataFile, $noDataMessage = 'No data' ) {

        // Manage No metadata file
        if ( !$metadataFile ) {

            // Get call stack
            $backTrace = debug_backtrace();
            
            // Log error for dev
            Log_LIB::trace('[Board_LIB __construct] No metadata file for class [' . $backTrace[1]['class'] . ']');
            
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

        $this->data          = $data;
        $this->metadata      = $metadata;
        $this->noDataMessage = $noDataMessage;
    }

    // Display board (for template)
    public function display() {

        // Start table
        $toDisplay = "<table class=\"table table-striped table-hover table-bordered table-condensed\">\n";

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

        return $toDisplay;
    }
}
