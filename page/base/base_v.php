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
        $cssFiles[] = SITE_ROOT . '/page/base/base.css';

        // Add css/javascript files for Bootstrap
        //---------------------------------------
        if ( WEBSITE_IS_ONLINE ) {
            
            $cssFiles[] = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css';
            $cssFiles[] = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css';

            $scriptFiles[] = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js';
        }
        else {
            $cssFiles[] = SITE_ROOT . '/page/base/bootstrap/css/bootstrap.min.css';
            $cssFiles[] = SITE_ROOT . '/page/base/bootstrap/css/bootstrap-theme.min.css';

            $scriptFiles[] = SITE_ROOT . '/page/base/bootstrap/js/bootstrap.min.js';
        }

        // Add Javascript files for every page
        //------------------------------------
        $scriptFiles[] = SITE_ROOT . '/page/base/base.js';

        // Add css/javascript files for header
        //------------------------------------
        if ( $header ) {

            if ( file_exists( $file = 'page/base/header/' . $header . '/' . $header . '.css' ) ) {
                $cssFiles[] = SITE_ROOT . '/' . $file;
            }
            
            if ( file_exists( $file = 'page/base/header/' . $header . '/' . $header . '.js' ) ) {
                $scriptFiles[] = SITE_ROOT . '/' . $file;
            }
        }

        // Add css/javascript files for footer
        //------------------------------------
        if ( $footer ) {

            if ( file_exists( $file = 'page/base/footer/' . $footer . '/' . $footer . '.css' ) ) {
                $cssFiles[] = SITE_ROOT . '/' . $file;
            }
            
            if ( file_exists( $file = 'page/base/footer/' . $footer . '/' . $footer . '.js' ) ) {
                $scriptFiles[] = SITE_ROOT . '/' . $file;
            }
        }

        // Add css/javascript files for templates
        //---------------------------------------
        foreach( $templates as $template ) {

            if ( file_exists( $file = 'page/' . $this->page . '/' . $template . '.css' ) ) {
                $cssFiles[] = SITE_ROOT . '/' . $file;
            }

            if ( file_exists( $file = 'page/' . $this->page . '/' . $template . '.js' ) ) {
                $scriptFiles[] = SITE_ROOT . '/' . $file;
            }
        }

        /************************************************** Add template **************************************************/
        // What is always on top
        require 'page/base/top_t.php';

        // Add header if needed
        if ( $header ) {

            require 'page/base/header/' . $header . '/' . $header . '_t.php';
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

            require 'page/base/footer/' . $footer . '/' . $footer . '_t.php';
        }

        // What is always on bottom
        require 'page/base/bottom_t.php';
        
        // TBD: force leave script?
    }
}
