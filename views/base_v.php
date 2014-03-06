<?php

/**
 * Mother class of every view
 */
class Base_View {

    // Holds variables assigned to template
    private $data;

    // Holds render status of view.
    private $render;

    // Header management
    protected function isHeader() {
        return true;
    }

    // Footer management
    protected function isFooter() {
        return true;
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
        
        $this->data = array();

        $page = strtolower(str_replace( '_View', '', get_called_class()));

        // Setting specific data
        $this->assign('page', $page);
        $this->assign('title', ucfirst($page));

        // Template file
        if ( !file_exists( $templateFile = 'views/templates/' . $page . '_t.php' ) )
        {
            Error_Library::launch( "Template file doesn't exists : $templateFile" );
            exit();
        }
        $this->render = $templateFile;
    }

    /**
     * Render the output directly to the page
     */
    public function render()
    {
        // Parse data variables into local variables
        $data = $this->data;

        // What is always on top
        include( 'views/templates/commun/top_t.php');

        if (static::isHeader())
            include( 'views/templates/commun/header_t.php');

        // Get template
        include( $this->render );

        if (static::isFooter())
            include( 'views/templates/commun/footer_t.php');

        // What is always on bottom
        include( 'views/templates/commun/bottom_t.php');
    }
}
