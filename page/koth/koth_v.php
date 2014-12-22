<?php

class Koth_PAG_V extends Base_PAG_V {

    // Add templates
    protected function getExtraTemplates() {

        $data = $this->getData();
        $playerNumber = count( $data['players'] );

        return array( $playerNumber . '_player');
    }
}
