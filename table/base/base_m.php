<?php

// For interface with this class
define('BTM_OK', 1);
define('BTM_KO', 2);

// Mother of all table Model
abstract class Base_Table_Model extends Base_Library_Model {
    
    static protected $tableName;
    
    static private $parameters = array();

    static protected function addParameter( $name, $type, $size = NULL, $nullable = FALSE, $default = NULL ) {
        static::$parameters[] = array( 'name'     => $name,
                                       'type'     => $type,
                                       'size'     => $size,
                                       'nullable' => $nullable,
                                       'default'  => $default );
    }

    static public function createTable() {

        // No table name
        if ( !static::$tableName ) {

            return BTM_KO;
        }

        // No table parameters
        if ( !static::$parameters ) {

            return BTM_KO;
        }
        
        // Start script
        $sql = "CREATE TABLE IF NOT EXISTS `" . static::$tableName . "` (";

        foreach( static::$parameters as $parameter ) {
            $sql .= '`' . $parameter['name']. '` '
                . $parameter['type']
                . ( $parameter['size'] ? '(' . $parameter['size'] . ') ' : ' ' )
                . $parameter['nullable']
                . ( $parameter['default'] ? 'DEFAULT ' . $parameter['default'] : '' )
                . ',';
        }

        // Remove last coma
        $sql = substr( $sql, 0, -1 );

        // End script
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        
        // Execute script
        
        // Return OK
        return BTM_OK;
    }
}
