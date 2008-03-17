<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 4.3.2 or newer
 *
 * @package		BackendPro
 * @author			Adam Price
 * @copyright		Copyright (c) 2008
 * @license			http://www.gnu.org/licenses/lgpl.html
 * @tutorial			BackendPro.pkg
 */

 // ---------------------------------------------------------------------------

/**
 * User_model
 *
 * Provides functionaly to query all tables related to the
 * user.
 *
 * @package			BackendPro
 * @subpackage		Models
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
			$this->_TABLES = array(	'Users' => $this->_prefix . 'users',
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
		 * Get User Session Data
		 *
		 * Fetch the users data that will be stored in their session
		 * variable.
		 *
		 * @access public
		 * @param integer $user_id Users user_id
		 * @return array
		 */
		function getUserSessionData($id)
		{
			$query = $this->fetch('Users','id,username,email,group,last_visit,created,modified',NULL,array('id'=>$id));
			return $query->row_array();
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
	}
?>