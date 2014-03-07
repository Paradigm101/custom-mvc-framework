<?php

/**
 * Mother class of every view
 */
abstract class Base_View {

    // Holds variables assigned to template
    private $data;

    // Holds the various templates to display
    private $templates;

    // Header management
    protected $header;

    // Footer management
    protected $footer;

    // Add a template to the page
    protected function addTemplate( $template ) {

        // Carefull : storing template name instead of template file
        // for front simplification
        $this->templates[] = $template;
    }

    public function setTitle( $title )
    {
        $this->assign('title', $title);
    }

    /**
     * Receives assignments from controller and stores in local data array
     */
    public function assign( $name , $value ) {
        $this->data[ $name ] = $value;
    }

    // Accept a template to load
    public function __construct() {
        
        $this->render = array();
        $this->data   = array();

        $page = strtolower(str_replace( '_View', '', get_called_class()));

        // Setting title
        $this->assign('page', $page);
        $this->assign('title', ucfirst($page));

        // Add main template file to render list IN THE FIRST POSITION
        $this->addTemplate( $page );
        
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

        // To manage CSS/Javascript inclusion
        $data[ 'templates' ] = $this->templates;
        
        // What is always on top
        include( 'includes/templates/commun/top_t.php');

        // Add header if needed
        if ( $this->header )
            include( 'includes/templates/commun/' . $this->header . '_t.php');

        // Add template(s)
        foreach( $this->templates as $template ) {

            // Check file exists
            if ( !file_exists( $templateFile = 'includes/templates/' . $template . '_t.php' ) ) {

                Error_Library::launch( "Template file doesn't exists : $templateFile" );
                exit();
            }

            // Core job: finally include template(s)
            include( $templateFile );
        }

        // Add footer if needed
        if ( $this->footer )
            include( 'includes/templates/commun/' . $this->footer . '_t.php');

        // What is always on bottom
        include( 'includes/templates/commun/bottom_t.php');
    }
}
