<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 4.3.2 or newer
 *
 * @package         BackendPro
 * @author          Adam Price
 * @copyright       Copyright (c) 2008
 * @license         http://www.gnu.org/licenses/lgpl.html
 */

 // ---------------------------------------------------------------------------

/**
 * User_model
 *
 * Provides functionaly to query all tables related to the
 * user.
 *
 * @package         BackendPro
 * @subpackage      Models
 */
    class User_model extends Base_model
    {
        /**
         * Constructor
         */
        function User_model()
        {
            // Inherit from parent class
            parent::Base_model();

            $this->_prefix = $this->config->item('backendpro_table_prefix');
            $this->_TABLES = array(    'Users' => $this->_prefix . 'users',
                                    'UserProfiles' => $this->_prefix . 'user_profiles');

            log_message('debug','User_model Class Initialized');
        }
        
        /**
         * Validate Login
         *
         * Verify that the given $username & $password are valid
         * for some user.
         *
         * @access public
         * @param string $username Users username
         * @param string $password Users password
         * @return Query
         */
        function validateLogin($email, $password)
        {
            return $this->fetch('Users','id,active',null,array('email'=>$email,'password'=>$password));
        }

        /**
         * Update Login Date
         *
         * Updates a users last_visit record to the current time
         *
         * @access public
         * @param integer $user_id Users user_id
         * @return void
         */
        function updateUserLogin($id)
        {
            $this->update('Users',array('last_visit'=>date ("Y-m-d H:i:s")),array('id'=>$id));
            return;
        }
        
        /**
        * Valid Email
        * 
        * Checks the given email is one that belongs to a valid email
        * 
        * @access public
        * @param string $email Email to validate
        * @return boolean
        */
        function validEmail($email)
        {
            $query = $this->fetch('Users',NULL,NULL,array('email'=>$email));
            return ($query->num_rows() == 0) ? FALSE : TRUE;
        }
        
        /**
        * Activate User Account
        * 
        * When given an activation_key, make that user account active
        * 
        * @access public
        * @param string $key Activation Key
        * @return boolean
        */
        function activateUser($key)
        {
            $this->update('Users', array('active'=>'1','activation_key'=>NULL), array('activation_key'=>$key));
            
            return ($this->db->affected_rows() == 1) ? TRUE : FALSE;
        }
        
        /**
         * Get Users
         * 
         * @access public
         * @param mixed $where Where query string/array
         * @param array $limit Limit array including offset and limit values
         * @return object 
         */
        function getUsers($where = NULL, $limit = array('limit' => NULL, 'offset' => ''))
        {
            // Load the khacl config file so we can get the correct table name
            $this->load->module_config('auth','khacl');
            $acl_tables = $this->config->item('acl_tables');
            
            $this->db->select('users.id, users.username, users.email, users.active, users.last_visit, users.created, users.modified, groups.name `group`, groups.id group_id');
            $this->db->from($this->_TABLES['Users'] . " users");
            //$this->db->join($this->_TABLES['UserProfiles'] . " profiles",'users.id=profiles.user_id');
            $this->db->join($acl_tables['aros'] . " groups",'groups.id=users.group');
            if( ! is_null($where)){ $this->db->where($where);}
            if( ! is_null($limit['limit'])){ $this->db->limit($limit['limit'],( isset($limit['offset'])?$limit['offset']:''));}
            return $this->db->get();
        }
        
        /**
        * Delete Users
        * 
        * Extend the delete users function to make sure we delete all data related
        * to the user
        * 
        * @access private
        * @param mixed $where Delete user where
        * @return boolean
        */
        function _delete_Users($where)
        {
            // Get the ID's of the users to delete          
            $query = $this->fetch('Users','id',NULL,$where);
            foreach($query->result() as $row)
            {
                $this->db->trans_begin();
                // -- ADD USER REMOVAL QUERIES/METHODS BELOW HERE
                
                // Delete main user details
                $this->db->delete($this->_TABLES['Users'],array('id'=>$row->id)); 
                
                // Delete user profile
                $this->delete('UserProfiles',array('user_id'=>$row->id)); 

                // -- DON'T CHANGE BELOW HERE
                // Check all the tasks completed
                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    return FALSE;
                } else {
                    $this->db->trans_commit();
                } 
            }
            return TRUE;
        }
    }
?>