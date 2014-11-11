<?php

// For interface with this class
define('BTM_OK', 1);
define('BTM_KO', 2);

// Mother of all table Model
class Base_Table_Model extends Base_Library_Model {

    protected $tableName;

    private $parameters;

    // TBD: Throw error, must be overwritten
    protected function initTable() {}

    public function __construct() {

        parent::__construct();

        static::initTable();
    }

    protected function addParameter( $name, $type, $size = null, $nullable = false, $default = true, $autoIncrement = false ) {
        $this->parameters[] = array( 'name'          => $name,
                                     'type'          => $type,
                                     'size'          => $size,
                                     'nullable'      => $nullable,
                                     'default'       => $default,
                                     'autoIncrement' => $autoIncrement );
    }

    public function createTable() {

        // Start query
        $sql = "CREATE TABLE IF NOT EXISTS `" . $this->tableName . "` (\n";

        // For each parameter, create a table field
        foreach( $this->parameters as $parameter ) {

            $sql .= '`' . $parameter['name']. '` '
                . $parameter['type']
                . ( $parameter['size'] ? '(' . $parameter['size'] . ') ' : ' ' )
                . ( $parameter['nullable'] ? '' : 'NOT NULL ' )
                . ( $parameter['default'] ? 'DEFAULT ' . $parameter['default'] : '' )
                . ( $parameter['autoIncrement'] ? 'AUTO_INCREMENT ' : '' )
                . ",\n";
        }

        // Remove last coma + CR
        $sql = substr( $sql, 0, -2 );

        // End query
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;\n";

        // TBD Execute query
        Log_Library_Controller::trace($sql);

        // Return OK
        return BTM_OK;
    }
}
