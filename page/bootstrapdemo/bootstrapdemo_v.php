<?php

class Bootstrapdemo_PAG_V extends Base_PAG_V {

    // Core method
    protected function process() {
        
        // Very important!
        parent::process();

        // Add specific templates
        $this->addTemplate( 'carousel' );
        $this->addTemplate( 'next' );

        // Set page title
        $this->setTitle('Bootstrap');
    }
}
