<?php

// Starting/Main page
class Main_PAG_V extends Base_PAG_V {

    // Core method
    protected function process() {
        
        // Very important!
        parent::process();

        // Set page title
        $this->setTitle('Home');
    }
}
