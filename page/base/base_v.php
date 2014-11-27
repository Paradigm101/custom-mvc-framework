<?php

define('BPV_ASSIGN_OK', 1);
define('BPV_ASSIGN_KO', 2);

/**
 * Mother class of every Page view
 * (i.e. every view bcuz only pages have a view)
 */
class Base_PAG_V {

    // Holds variables assigned to template
    private $data;

    // At creation, set parameters: template, header/footer, title, ...
    public function __construct( $page = null ) {

        // Initialize data and page name
        $this->data = array();

        // In case page has no view, controller provide with page name
        $this->data[ 'page' ] = $page ? $page : strtolower( str_replace( '_PAG_V', '', get_called_class()) );

        // Set title
        $this->data['title'] = ucfirst( $this->data[ 'page' ] );

        // Initialize templates with main file IN THE FIRST POSITION (but the file doesn't necessarily exits)
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
             $name == 'footer'    ||
             $name == 'css_files' ||
             $name == 'script_files' ) {

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

        // Preparing css/javascript files
        foreach( $this->data['templates'] as $template ) {

            // Getting css file name
            $cssFile = 'page/' . $this->data['page'] . '/' . $template . '.css';

            // If file exists, add it to be loaded
            if ( file_exists( $cssFile ) ) {

                $this->data['css_files'][] = $cssFile;
            }

            // Getting javascript file name
            $scriptFile = 'page/' . $this->data['page'] . '/' . $template . '.js';

            // If file exists, add it to be loaded
            if ( file_exists( $scriptFile ) ) {

                $this->data['script_files'][] = $scriptFile;
            }
        }

        // Add header css/js if needed
        if ( $this->data[ 'header' ] ) {

            // Getting css file name
            $cssFile = 'page/' . $this->data['header'] . '/' . $this->data['header'] . '.css';

            // If file exists, add it to be loaded
            if ( file_exists( $cssFile ) ) {

                $this->data['css_files'][] = $cssFile;
            }

            // Getting javascript file name
            $scriptFile = 'page/' . $this->data['header'] . '/' . $this->data['header'] . '.js';

            // If file exists, add it to be loaded
            if ( file_exists( $scriptFile ) ) {

                $this->data['script_files'][] = $scriptFile;
            }
        }

        // Add footer css/js if needed
        if ( $this->data[ 'footer' ] ) {

            // Getting css file name
            $cssFile = 'page/' . $this->data['footer'] . '/' . $this->data['footer'] . '.css';

            // If file exists, add it to be loaded
            if ( file_exists( $cssFile ) ) {

                $this->data['css_files'][] = $cssFile;
            }

            // Getting javascript file name
            $scriptFile = 'page/' . $this->data['footer'] . '/' . $this->data['footer'] . '.js';

            // If file exists, add it to be loaded
            if ( file_exists( $scriptFile ) ) {

                $this->data['script_files'][] = $scriptFile;
            }
        }

        // Send data to front for templates
        // To do BEFORE requiring the templates
        $data = $this->data;

        // What is always on top
        require 'page/base/top_t.php';

        // Add header if needed
        if ( $this->data[ 'header' ] ) {

            require 'page/' . $this->data[ 'header' ] . '/' . $this->data[ 'header' ] . '_t.php';
        }

        // Add template(s)
        foreach( $this->data[ 'templates' ] as $template ) {

            // Check file exists
            if ( file_exists( $templateFile = 'page/' . $this->data[ 'page' ] . '/' . $template . '_t.php' ) ) {

                // Core job: finally include template(s)
                require $templateFile;
            }
        }

        // Add footer if needed
        if ( $this->data['footer'] ) {

            require 'page/' . $this->data['footer'] . '/' . $this->data['footer'] . '_t.php';
        }

        // What is always on bottom
        require 'page/base/bottom_t.php';
    }
}
