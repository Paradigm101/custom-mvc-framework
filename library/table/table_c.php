<?php

// Providing a nice table
class Table_Library_Controller {

    public function display() {

        // TBD: retrive data from DB through parameters
        $header = array('data1', 'data2', 'data3', 'data4', 'data5', 'data6');

        // Start table
        $toDisplay = "<table class=\"table table-striped table-hover table-bordered\">\n";

        // Header
        //-------
        $toDisplay .= "<thead>\n"
                        . "<tr>\n"
                            . "<th></th>\n";

        foreach( $header as $column ) {
            $toDisplay .= "<th>$column</th>\n";
        }

        $toDisplay .= "</tr>\n"
                . "</thead>\n";

        // Body
        //-----
        $toDisplay .= "<tbody>\n";

        // Row
        for( $i = 0; $i < 30; $i++ ) {

            // Start with the checkbox
            $toDisplay .= "<tr>\n"
                            . "<td><input type=\"checkbox\" id=\"checkbox_$i\"></td>\n";

            foreach( $header as $key => $column ) {
                $toDisplay .= "<td>" . ( $i + 1 ) . ( $key + 1 ) * rand(1, 5) . "</td>\n";
            }

            $toDisplay .= "</tr>\n";
        }

        $toDisplay .= "</tbody>\n";

        // End table
        $toDisplay .= "</table>\n";

        return $toDisplay;
    }
}
