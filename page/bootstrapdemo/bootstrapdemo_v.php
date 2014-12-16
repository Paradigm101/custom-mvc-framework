<?php

class Bootstrapdemo_PAG_V extends Base_PAG_V {

    // Set title
    protected function getTitle() {
        return 'Bootstrap';
    }

    // Add templates
    protected function getExtraTemplates() {

        return array('carousel', 'next');
    }
}
