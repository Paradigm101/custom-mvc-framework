<?php

define('BPV_ASSIGN_OK', 1);
define('BPV_ASSIGN_KO', 2);

/**
 * Mother class of every Page view
 * (i.e. every view bcuz only pages have a view)
 * this class is abstract but children are meant to be instanciated, hence the '$this->' on properties
 */
abstract class Base_PAG_V {

    // Holds variables assigned to template
    private $data;

    // At creation, set parameters: template, header/footer, title, ...
    public function __construct() {

        // Initialize data and page name
        $this->data = array();
        $this->data[ 'page' ] = strtolower( str_replace( '_PAG_V', '', get_called_class()) );

        // Set title
        $this->data['title'] = ucfirst( $this->data[ 'page' ] );

        // Initialize templates with main file IN THE FIRST POSITION
        $this->data[ 'templates' ] = array( $this->data[ 'page' ] );

        // Default header/footer
        $this->data[ 'header' ] = DEFAULT_HEADER;
        $this->data[ 'footer' ] = DEFAULT_FOOTER;
    }

    // Add a template to the page
    protected function addTemplate( $template ) {

        // Carefull : storing template name instead of template file for front-end simplification
        array_push( $this->data[ 'templates' ], $template );
    }

    // Set title
    protected function setTitle( $title ) {

        $this->data[ 'title' ] = $title;
    }

    // Collect data from controller and child view
    // TBD: should be protected?
    public function assign( $name , $value ) {

        // Reserved data
        if ( $name == 'templates' ||
             $name == 'page'      ||
             $name == 'title'     ||
             $name == 'header'    ||
             $name == 'footer' ) {

            // Log and return KO
            Log_LIB::trace("[Base_PAG_V] Trying to assign protected field in View [$name]");
            return BPV_ASSIGN_KO;
        }

        // Assign and return OK
        $this->data[ $name ] = $value;
        return BPV_ASSIGN_OK;
    }

    /**
     * Render the output directly to the page
     */
    public function render() {

        // Send data to front for templates: To do first!
        $data = $this->data;

        // What is always on top
        require 'page/base/template/top.php';

        // Add header if needed
        if ( $data[ 'header' ] ) {
            require 'page/' . $data[ 'header' ] . '/template/' . $data[ 'header' ] . '.php';
        }

        // Add template(s)
        foreach( $data[ 'templates' ] as $template ) {

            // Check file exists
            if ( !file_exists( $templateFile = 'page/' . $data[ 'page' ] . '/template/' . $template . '.php' ) ) {

                $errorMsg = "Template file doesn't exists : $templateFile";
                Log_LIB::trace( '[Base_PAG_V] ' . $errorMsg );
                echo $errorMsg, ALL_EOL;
            }
            // Core job: finally include template(s)
            else {
                require $templateFile;
            }
        }

        // Add footer if needed
        if ( $data['footer'] ) {
            require 'page/' . $data['footer'] . '/template/' . $data['footer'] . '.php';
        }

        // What is always on bottom
        require 'page/base/template/bottom.php';
    }
}
