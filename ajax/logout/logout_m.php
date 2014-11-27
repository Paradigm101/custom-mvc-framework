<?php

class Logout_AJA_M extends Base_LIB_Model {

    // Remove session: logout user
    public function removeCurrentSession() {

        // Sanitize data and add quotes
        $id_session = $this->getQuotedValue(session_id());

        // Retrieve previous user session
        $this->query( "DELETE FROM sessions WHERE id_session = $id_session" );
    }
}
