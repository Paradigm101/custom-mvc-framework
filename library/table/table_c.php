<?php

// Providing a nice table
class Table_Library_Controller {

    public function display() {

        // TBD: retrive data from DB through parameters
        $header = array('data1', 'data2', 'data3', 'data4', 'data5', 'data6');

        // Start table
        $toDisplay = "<table class=\"table table-striped table-bordered table-hover\">";

        // Header
        $toDisplay .= "<thead><tr><th></th>";

        foreach( $header as $column ) {
            $toDisplay .= "<th>$column</th>";
        }
        
        $toDisplay .= "</tr></thead>";
        
        // Row
        for( $i = 0; $i < 30; $i++ ) {
            
            // Start with the checkbox
            $toDisplay .= "<tr><td><input type=\"checkbox\" id=\"checkbox_" . $i . "\"/></td>";
            
            for ( $j = 0; $j < 6; $j++ ) {
                $toDisplay .= "<td>" . rand(0, 100) . "</td>";
            }
            
            $toDisplay .= "</tr>";
        }
        
        // End table
        $toDisplay .= "</table>";

        return $toDisplay;
    }
}
