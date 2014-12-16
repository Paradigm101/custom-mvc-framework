<?php

// Mother class of every Page view
class Base_PAG_V extends Base_LIB_View {

    // Page name
    private $page = '';

    // Need page name to work!
    public function setPageName( $pageName ) {

        $this->page = strtolower( $pageName );
    }

    // Title: override in children to change title
    protected function getTitle() {

        return ucfirst( $this->page );
    }

    // Templates: override in children to add templates
    protected function getExtraTemplates() {

        return array();
    }

    // Header: override in children for a different header
    protected function getHeader() {

        return DEFAULT_HEADER;
    }

    // Footer: override in children for a different footer
    protected function getFooter() {

        return DEFAULT_FOOTER;
    }

    // display page:
    //      prepare data, css files and javascript files for templates
    //      load templates
    public function render() {

        /******************************** DATA FOR TEMPLATES **********************************************/
        // To do BEFORE including the templates
        $cssFiles    = array();
        $scriptFiles = array();
        $data        = $this->getData();
        $title       = $this->getTitle();
        $header      = $this->getHeader();
        $footer      = $this->getFooter();
        $templates   = array_merge( array( strtolower( $this->page ) ),
                                    $this->getExtraTemplates() );

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
        if ( $header ) {

            // Getting css file name
            $cssFile = 'page/' . $header . '/' . $header . '.css';

            // If file exists, add it to be loaded
            if ( file_exists( $cssFile ) ) {

                $cssFiles[] = $cssFile;
            }

            // Getting javascript file name
            $scriptFile = 'page/' . $header . '/' . $header . '.js';

            // If file exists, add it to be loaded
            if ( file_exists( $scriptFile ) ) {

                $scriptFiles[] = $scriptFile;
            }
        }

        // Add css/javascript files for footer
        //------------------------------------
        if ( $footer ) {

            // Getting css file name
            $cssFile = 'page/' . $footer . '/' . $footer . '.css';

            // If file exists, add it to be loaded
            if ( file_exists( $cssFile ) ) {

                $cssFiles[] = $cssFile;
            }

            // Getting javascript file name
            $scriptFile = 'page/' . $footer . '/' . $footer . '.js';

            // If file exists, add it to be loaded
            if ( file_exists( $scriptFile ) ) {

                $scriptFiles[] = $scriptFile;
            }
        }

        // Add css/javascript files for templates
        //---------------------------------------
        foreach( $templates as $template ) {

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
        if ( $header ) {

            require 'page/' . $header . '/' . $header . '_t.php';
        }

        // Base template
        require 'page/base/base_t.php';
        
        // Page template(s)
        foreach( $templates as $template ) {

            // Check file exists
            if ( file_exists( $templateFile = 'page/' . $this->page . '/' . $template . '_t.php' ) ) {

                // Core job: finally include template(s)
                require $templateFile;
            }
        }

        // Add footer if needed
        if ( $footer ) {

            require 'page/' . $footer . '/' . $footer . '_t.php';
        }

        // What is always on bottom
        require 'page/base/bottom_t.php';
    }
}
