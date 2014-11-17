<?php

// Manage board display
class Board_LIB_View {

    // Data to display
    private $data;

    // For interface
    private $fields;

    // Set data
    public function __construct( $data, $fields ) {

        $this->data   = $data;
        $this->fields = $fields;
    }

    // Display data
    public function display() {

        // Start table
        $toDisplay = "<table class=\"table table-striped table-hover table-bordered table-condensed\">\n";

        // Start header
        $toDisplay .= "<thead>\n"
                        . "<tr>\n"
                            . "<th></th>\n";

        // Display each field title
        foreach( $this->data[0] as $key => $value ) {

            $toDisplay .= "<th>" . ucfirst( $this->fields[ $key ]['label'] ) . "</th>\n";
        }

        // End header
        $toDisplay .= "</tr>\n"
                . "</thead>\n";

        // Start body
        $toDisplay .= "<tbody>\n";

        // Display all rows
        foreach( $this->data as $key => $row ) {

            // Start with the checkbox
            $toDisplay .= "<tr>\n"
                            . "<td><input type=\"checkbox\" id=\"checkbox_$key\"></td>\n";

            // Display each fields
            foreach( $row as $value ) {

                $toDisplay .= "<td>" . $value . "</td>\n";
            }

            // End row
            $toDisplay .= "</tr>\n";
        }

        // End body
        $toDisplay .= "</tbody>\n";

        // End table
        $toDisplay .= "</table>\n";

        return $toDisplay;
    }
}
