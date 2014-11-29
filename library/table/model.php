<?php

define('TLM_INIT_SKIP',            0);
define('TLM_INIT_AUTO_WITH_ID',    1);
define('TLM_INIT_AUTO_WITHOUT_ID', 2);
define('TLM_INIT_CUSTOM',          3);

// Mother of all table Model
abstract class Table_LIB_Model extends Base_LIB_Model {

    // Return initializing mode, can be overwritten
    protected function getInitMode() {

        // By default, no initialization
        return TLM_INIT_SKIP;
    }

    // Return initializing script for custom init
    protected function getInitScript() {

        // By default, no script
        return '';
    }

    // Get table name, must be overwritten
    protected function getTableName() {

        Log_LIB::trace('[Table_LIB_Base] Method ' . __METHOD__ . ' has to be overwritten from [' . get_called_class() . ']');
    }

    // To execute any commande: check parameters and manage DB errors
    private function executeQuery( $sql ) {

        // Sanity check on table name to save time
        if ( !$this->getTableName() ) {

            // Log for debug
            Log_LIB::trace('[Table_LIB_Base] tableName has not been initialized in [' . get_called_class() . ']');

            // Send error to user
            return 'Internal error';
        }

        // Execute query
        if ( !$this->query($sql) ) {

            // If error, return it
            return $this->getLastErrorForUser();
        }

        // Return no error
        return '';
    }

    // delete table, for controller
    public function deleteTable() {

        // Query
        $sql = 'DROP TABLE ' . $this->getTableName();

        // Execute query and return error
        return $this->executeQuery($sql);
    }

    // Get CSV config file
    private function getConfigFile( $csvFile ) {
        
        // No table descriptor file
        if ( !file_exists( $csvFile ) ) {

            // Get table name
            if ( !($tableName = $this->getTableName() ) ) {
                
                // If no table name, get class name
                $tableName = get_called_class();
            }

            // Log data for debug
            Log_LIB::trace("[Table_LIB_Base] [$tableName] config file does not exists [$csvFile]");

            // Return error for user
            return null;
        }

        // Return config file
        return fopen( $csvFile, 'r' );
    }

    // create table, for controller
    public function createTable() {

        // Get table descriptor file <table_name>_d.csv
        if ( !( $csvFileStream = $this->getConfigFile( 'library/table/' . strtolower( str_replace( 'Table_LIB_', '', get_called_class() ) ) . '_d.csv' ) ) ) {
            
            // In case of problem, return error for user
            return 'Internal error';
        }
        
        // Parsing file and getting field descriptions
        while ( $data = fgetcsv( $csvFileStream ) ) {

            // Add field
            $fields[] = array( 'name'          => trim( $data[0] ),
                               'type'          => trim( $data[1] ),
                               'size'          => trim( $data[2] ),
                               'default'       => trim( $data[3] ),
                               'nullable'      => trim( $data[4] ) ? true : false,
                               'autoIncrement' => trim( $data[5] ) ? true : false,
                               'isPrimaryKey'  => trim( $data[6] ) ? true : false,
                               'isUniqueKey'   => trim( $data[7] ) ? true : false );
        }

        // Start query
        $sql = "CREATE TABLE `" . $this->getTableName() . '` (';

        // For each field definition, create a table field
        foreach( $fields as $field ) {

            $sql .= '`' . $field['name']. '` '
                . $field['type']
                . ( $field['size'] ? '(' . $field['size'] . ') ' : ' ' )
                . ( $field['nullable'] ? '' : 'NOT NULL ' )
                . ( $field['default'] ? 'DEFAULT ' . $field['default'] : '' )
                . ( $field['autoIncrement'] ? 'AUTO_INCREMENT ' : '' )
                . ',';
        }

        // Manage indexes and other funny stuff
        foreach( $fields as $field ) {

            // Primary key
            if ( $field['isPrimaryKey'] ) {

                $sql .= 'PRIMARY KEY (`' . $field['name'] . '`),';
            }
            // Unique key
            if ( $field['isUniqueKey'] ) {

                $sql .= "UNIQUE KEY `" . $field['name'] . "` (`" . $field['name'] . '`),';
            }
        }

        // Remove last coma
        $sql = substr( $sql, 0, -1 );

        // End query
        $sql .= ') ENGINE=InnoDB DEFAULT CHARSET=latin1;';

        // Execute query: create table
        //----------------------------
        if ( $error = $this->executeQuery($sql) ) {

            // Error: stop here and return info for user
            return $error;
        }

        // No init: stop here and return no error
        if ( $this->getInitMode() == TLM_INIT_SKIP ) {

            return '';
        }
        
        // Custom init: run script and return error
        if ( $this->getInitMode() == TLM_INIT_CUSTOM ) {

            return $this->executeQuery( $this->getInitScript() );
        }
        
        // Auto init with data file
        //-------------------------

        // Get table initialize file <table_name>_i.csv
        if ( !( $csvFileStream = $this->getConfigFile( 'library/table/' . strtolower( str_replace( 'Table_LIB_', '', get_called_class() ) ) . '_i.csv' ) ) ) {

            // In case of problem, return error for user
            return 'Internal error';
        }

        // Init script
        $sql = 'INSERT INTO ' . $this->getTableName() . ' (';

        // Parse each field
        foreach ( $fields as $field ) {

            // Add field if we are adding ID or if it's not the ID field
            if ( ( $this->getInitMode() == TLM_INIT_AUTO_WITH_ID )
              || ( $field['name'] != 'id' ) )
            {
                $sql .= $field['name'] . ',';
            }
        }

        // Remove last coma and continue
        $sql = substr( $sql, 0, -1 ) . ') VALUES ';

        // For each line in the init script, add values
        while ( $elements = fgetcsv( $csvFileStream ) ) {

            // start values
            $sql .= '(';

            // Get each value and add it, manage numeric values
            for ( $i = 0; $i < count($fields); $i++ ) {
                
                // If this is the ID and we are not processing the IDs
                // don't do anything and continue the loop
                if ( ( $this->getInitMode() == TLM_INIT_AUTO_WITHOUT_ID )
                  && ( $fields[$i]['name'] == 'id' ) ) {
                    continue;
                }
                
                // Get data
                $element = trim( array_shift( $elements ) );
                
                // Manage numeric
                if ( in_array($fields[$i]['type'], array('tinyint', 'smallint', 'mediumint', 'int', 'bigint', 'decimal', 'float', 'double', 'real') ) ) {
                    $element += 0;
                }

                // Manage boolean
                if ( $fields[$i]['type'] == 'boolean' ) {
                    $element = ( $element && ( strtolower($element) != 'false' ) ? true : false );
                }

                $sql .= $this->getQuotedValue( $element ) . ',';
            }
            
            // Remove last coma and continue
            $sql = substr( $sql, 0, -1 ) . '),';
        }
        
        // Remove last coma and finish
        $sql = substr( $sql, 0, -1 );

        // Execute population script and return error if any
        return $this->executeQuery( $sql );
    }
}
