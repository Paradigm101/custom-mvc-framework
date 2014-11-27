<?php

class Bootstrapdemo_PAG_V extends Base_PAG_V {

    public function __construct() {

        parent::__construct();

        // Add specific templates
        $this->addTemplate( 'carousel' );
        $this->addTemplate( 'next' );

        // Set page title
        $this->setTitle( 'Bootstrap' );
    }
}
