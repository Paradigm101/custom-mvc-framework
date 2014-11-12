<?php

class Bootstrapdemo_PAG_V extends Base_PAG_V {

    public function __construct() {

        parent::__construct();

        // Add specific templates
        $this->addTemplate( 'bootstrapdemo_carousel' );
        $this->addTemplate( 'bootstrapdemo_next' );
        $this->addTemplate( 'bootstrapdemo_end' );

        // Set page title
        $this->setTitle( 'Bootstrap' );
    }
}
