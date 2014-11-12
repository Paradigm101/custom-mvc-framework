<?php

// For interface with this class
define('BTM_OK', 1);
define('BTM_KO', 2);

// Mother of all table Model
abstract class Base_TAB extends Model_Base_LIB {

    protected $tableName;

    private $parameters;

    // Core method that define the table and must be overwritten
    protected function initTable() {

        Log_LIB::trace('[Base_TAB] Method ' . __METHOD__ . ' has to be overwritten from [' . get_called_class() . ']');
    }

    // Constructor: initialize table parameters
    public function __construct() {

        parent::__construct();

        static::initTable();
    }

    // add parameter, for children
    protected function addParameter( $name,
                                     $type,
                                     $size          = null,
                                     $nullable      = false,
                                     $default       = null,
                                     $autoIncrement = false,
                                     $isPrimaryKey  = false,
                                     $isUniqueKey   = false ) {

        $this->parameters[] = array( 'name'          => $name,
                                     'type'          => $type,
                                     'size'          => $size,
                                     'nullable'      => $nullable,
                                     'default'       => $default,
                                     'autoIncrement' => $autoIncrement,
                                     'isPrimaryKey'  => $isPrimaryKey,
                                     'isUniqueKey'   => $isUniqueKey );
    }

    // Set uniqueness
    public function setUnique( $param, $value = true ) {

        foreach ( $this->parameters as &$parameter ) {
            
            if ( $parameter[ 'name' ] == $param ) {

                $parameter[ 'isUniqueKey' ] = $value;
            }
        }
    }

    // To execute any commande: check parameters and manage DB errors
    private function executeQuery( $sql ) {

        if ( !$this->tableName ) {

            Log_LIB::trace('[Base_TAB] tableName has not been initialized in [' . get_called_class() . ']');
            return BTM_KO;
        }
        if ( !$this->parameters ) {

            Log_LIB::trace('[Base_TAB] parameters have not been initialized in [' . get_called_class() . ']');
            return BTM_KO;
        }

        // Execute query
        if ( !$this->query($sql) ) {

            // Log error
            Log_LIB::trace($this->getLastError(), '[Base_TAB] Error in query : ' . $sql);

            return BTM_KO;
        }

        // Return OK
        return BTM_OK;
    }

    // delete table, for controller
    public function deleteTable() {

        // Query
        $sql = 'DROP TABLE ' . $this->tableName;

        // Execute query
        return $this->executeQuery($sql);
    }

    // create table, for controller
    public function createTable() {

        // Start query
        $sql = "CREATE TABLE IF NOT EXISTS `" . $this->tableName . '` (';

        // For each parameter, create a table field
        foreach( $this->parameters as $parameter ) {

            $sql .= '`' . $parameter['name']. '` '
                . $parameter['type']
                . ( $parameter['size'] ? '(' . $parameter['size'] . ') ' : ' ' )
                . ( $parameter['nullable'] ? '' : 'NOT NULL ' )
                . ( $parameter['default'] ? 'DEFAULT ' . $parameter['default'] : '' )
                . ( $parameter['autoIncrement'] ? 'AUTO_INCREMENT ' : '' )
                . ',';
        }

        // Manage indexes and other funny stuff
        foreach( $this->parameters as $parameter ) {

            if ( $parameter['isPrimaryKey'] ) {
                $sql .= 'PRIMARY KEY (`' . $parameter['name'] . '`),';
            }
            if ( $parameter['isUniqueKey'] ) {
                $sql .= "UNIQUE KEY `" . $parameter['name'] . "` (`" . $parameter['name'] . '`),';
            }
        }

        // Remove last coma
        $sql = substr( $sql, 0, -1 );

        // End query
        $sql .= ') ENGINE=InnoDB DEFAULT CHARSET=latin1;';

        // Execute query
        return $this->executeQuery($sql);
    }
}
