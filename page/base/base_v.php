<?php

/**
 * Mother class of every Page view
 * (i.e. every view bcuz only pages have a view)
 */
abstract class Base_Page_View {

    // Holds variables assigned to template
    private $data;

    // Holds the various templates to display
    private $templates;

    // Header management
    protected $header;

    // Footer management
    protected $footer;
    
    // Page name
    private $page;
    
    // Add a template to the page
    protected function addTemplate( $template ) {

        // Carefull : storing template name instead of template file
        // for front-end simplification
        $this->templates[] = $template;
    }

    public function setTitle( $title ) {
        $this->assign('title', $title);
    }

    /**
     * Receives assignments from controller and stores in local data array
     */
    public function assign( $name , $value ) {

        // Reserved data
        if ( $name == 'templates' ||
             $name == 'page' ) {
            Throw new Exception('Error: trying to assign protected field in View!');
        }

        $this->data[ $name ] = $value;
    }

    // Accept a template to load
    public function __construct() {
        
        $this->render = array();
        $this->data   = array();

        $this->page = strtolower( str_replace( '_Page_View', '', get_called_class()) );

        // Setting title
        $this->assign('title', ucfirst( $this->page ));

        // Add main template file to render list IN THE FIRST POSITION
        $this->addTemplate( $this->page );

        // Default header/footer
        $this->header = 'header';
        $this->footer = 'footer_custom';
    }

    /**
     * Render the output directly to the page
     */
    public function render()
    {
        // Parse data variables into local variables
        $data = $this->data;

        // Add header/footer
        $data[ 'header' ] = $this->header;
        $data[ 'footer' ] = $this->footer;

        // Page name
        $data[ 'page' ] = $this->page;
        
        // To manage CSS/Javascript inclusion
        $data[ 'templates' ] = $this->templates;

        // What is always on top
        require 'page/base/template/top_t.php';

        // Add header if needed
        if ( $this->header ) {
            require 'page/' . $this->header . '/template/' . $this->header . '_t.php';
        }

        // Add template(s)
        foreach( $this->templates as $template ) {

            // Check file exists
            if ( !file_exists( $templateFile = 'page/' . $this->page . '/template/' . $template . '_t.php' ) ) {

                $errorMsg = "Template file doesn't exists : $templateFile";
                Log_Library_Controller::trace( '[SYSTEM] ' . $errorMsg );
                echo $errorMsg, ALL_EOL;
            }
            // Core job: finally include template(s)
            else {
                require $templateFile;
            }
        }

        // Add footer if needed
        if ( $this->footer ) {
            require 'page/' . $this->footer . '/template/' . $this->footer . '_t.php';
        }

        // What is always on bottom
        require 'page/base/template/bottom_t.php';
    }
}
