<?php

/**
 * Manage specific data access for main page
 */
class Main_Model extends Base_Model
{
    /** Get existing users in DB
     * 
     * @param type $type: 'array' or 'object' or default
     * @return array of arrays or objects
     */
    public function getUsers($type = null)
    {
        $this->query('SELECT * FROM users');

        return $this->db->fetchAll($type);
    }

    /** Add user in DB
     * 
     * @param type $name
     * @param type $password
     * @return int (DB id or 0 if problem)
     */
    public function addUser($name, $password = null)
    {
        // Manage empty values and special characters
        $name     = $this->db->getQuotedValue($name);
        $password = $this->db->getQuotedValue($password);

        // Doing the job
        $success = $this->query( "INSERT INTO users (name, password) VALUES ($name, $password)" );

        // Success
        if ( $success )
            return $this->db->getInsertId();

        // No success
        return 0;
    }
}
