<?php

class Bootstrapdemo_View extends Base_View {
    public function __construct() {
        parent::__construct();
        
        $this->addTemplate( 'bootstrapdemo_carousel' );
        $this->addTemplate( 'bootstrapdemo_next' );
        $this->addTemplate( 'bootstrapdemo_end' );
    }
    
}
