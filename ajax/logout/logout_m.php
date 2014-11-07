<?php

class Logout_Ajax_Model extends Base_Ajax_Model {

    // Remove session: logout user
    public function removeCurrentSession() {

        // Sanitize data and add quotes
        $id_session = $this->db->getQuotedValue(session_id());

        // Retrieve previous user session
        $this->query( "DELETE FROM sessions WHERE id_session = $id_session" );
    }
}
