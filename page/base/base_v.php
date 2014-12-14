<?php

// Mother class of every Page view
class Base_PAG_V extends Base_LIB_View {

    // Internal information
    private $page      = '';
    private $title     = '';
    private $templates = array();
    private $header    = DEFAULT_HEADER;
    private $footer    = DEFAULT_FOOTER;

    // Need page name to work!
    public function setPageName( $pageName ) {

        $this->page = strtolower( $pageName );
    }

    // Init title and templates
    protected function process() {

        $this->title     = ucfirst( $this->page );
        $this->templates = array( strtolower( $this->page ) );
    }

    // Set title
    protected function setTitle( $title ) {

        $this->title = $title;
    }

    // Add a template to the page
    protected function addTemplate( $template ) {

        // Carefull : storing template name instead of template file (i.e. with path) for front-end simplification
        array_push( $this->templates, $template );
    }

    // display page:
    //      prepare data, css files and javascript files for templates
    //      load templates
    public function render() {

        // Allow children to do stuff
        $this->process();
        
        /******************************** DATA FOR TEMPLATES **********************************************/
        // To do BEFORE requiring the templates
        $data        = $this->getData();
        $title       = $this->title;
        $cssFiles    = array();
        $scriptFiles = array();
        
        // Add css files for every page
        //-------------------------
        $cssFiles[] = 'page/base/base.css';

        // Add css/javascript files for Bootstrap
        //---------------------------------------
        if ( WEBSITE_IS_ONLINE ) {
            
            $cssFiles[] = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css';
            $cssFiles[] = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css';

            $scriptFiles[] = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js';
        }
        else {
            $cssFiles[] = 'page/base/bootstrap/css/bootstrap.min.css';
            $cssFiles[] = 'page/base/bootstrap/css/bootstrap-theme.min.css';
            
            $scriptFiles[] = 'page/base/bootstrap/js/bootstrap.min.js';
        }

        // Add Javascript files for every page
        //------------------------------------
        $scriptFiles[] = 'page/base/base.js';

        // Add css/javascript files for header
        //------------------------------------
        if ( $this->header ) {

            // Getting css file name
            $cssFile = 'page/' . $this->header . '/' . $this->header . '.css';

            // If file exists, add it to be loaded
            if ( file_exists( $cssFile ) ) {

                $cssFiles[] = $cssFile;
            }

            // Getting javascript file name
            $scriptFile = 'page/' . $this->header . '/' . $this->header . '.js';

            // If file exists, add it to be loaded
            if ( file_exists( $scriptFile ) ) {

                $scriptFiles[] = $scriptFile;
            }
        }

        // Add css/javascript files for footer
        //------------------------------------
        if ( $this->footer ) {

            // Getting css file name
            $cssFile = 'page/' . $this->footer . '/' . $this->footer . '.css';

            // If file exists, add it to be loaded
            if ( file_exists( $cssFile ) ) {

                $cssFiles[] = $cssFile;
            }

            // Getting javascript file name
            $scriptFile = 'page/' . $this->footer . '/' . $this->footer . '.js';

            // If file exists, add it to be loaded
            if ( file_exists( $scriptFile ) ) {

                $scriptFiles[] = $scriptFile;
            }
        }

        // Add css/javascript files for templates
        //---------------------------------------
        foreach( $this->templates as $template ) {

            // Getting css file name
            $cssFile = 'page/' . $this->page . '/' . $template . '.css';

            // If file exists, add it to be loaded
            if ( file_exists( $cssFile ) ) {

                $cssFiles[] = $cssFile;
            }

            // Getting javascript file name
            $scriptFile = 'page/' . $this->page . '/' . $template . '.js';

            // If file exists, add it to be loaded
            if ( file_exists( $scriptFile ) ) {

                $scriptFiles[] = $scriptFile;
            }
        }

        /************************************************** Add template **************************************************/
        // What is always on top
        require 'page/base/top_t.php';

        // Add header if needed
        if ( $this->header ) {

            require 'page/' . $this->header . '/' . $this->header . '_t.php';
        }

        // Base template
        require 'page/base/base_t.php';
        
        // Page template(s)
        foreach( $this->templates as $template ) {

            // Check file exists
            if ( file_exists( $templateFile = 'page/' . $this->page . '/' . $template . '_t.php' ) ) {

                // Core job: finally include template(s)
                require $templateFile;
            }
        }

        // Add footer if needed
        if ( $this->footer ) {

            require 'page/' . $this->footer . '/' . $this->footer . '_t.php';
        }

        // What is always on bottom
        require 'page/base/bottom_t.php';
    }
}
