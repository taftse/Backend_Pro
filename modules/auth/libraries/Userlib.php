<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * BackendPro
     *
     * A website backend system for developers for PHP 4.3.2 or newer
     *
     * @package			BackendPro
     * @author			Adam Price
     * @copyright		Copyright (c) 2008
     * @license			http://www.gnu.org/licenses/lgpl.html
     */

     // ---------------------------------------------------------------------------

    /**
     * Userlib
     *
     * User authentication library used by BackendPro. Permits
     * protecting controllers/methods from un-authorized access.
     *
     * @package			BackendPro
     * @subpackage		Libraries
     */
	class Userlib
	{
		function Userlib()
		{
			// Get CI Instance
			$this->CI = &get_instance();

			// Load any files directly related to the authentication module
			$this->CI->lang->load('userlib');
			$this->CI->load->model('user_model');
			$this->CI->load->helper('userlib');
			
			// Load any other helpers/libraries needed
			$this->CI->load->helper('cookie');
            $this->CI->load->helper('Khacl');

			// Initialise the class
			$this->_init();
		}

		/**
		 * Initialise User Library
		 *
		 * Several jobs to perform
		 * > Check for autologin
         * > Delete un activated user accounts
		 *
		 * @access private
		 * @return void
		 */
		function _init()
		{
			// Log the user in if autologin details are correct
			if( !$this->is_user())
			{
				if (FALSE !== ($autologin = get_cookie('autologin')))
				{
					// Autologin data exists
					$autologin = unserialize($autologin);

					// Check its valid
					$query = $this->CI->user_model->validateLogin($autologin['email'],$autologin['password']);
					if($query->num_rows() == 1)
					{
						// Log user in
						$this->set_userlogin($autologin['id']);
                        log_message('debug','Logged user in using autologin cookie');
					}
				}
			}
            
            // Remove any user accounts which have not been activated
            // within the specified deadline
            $query = $this->CI->user_model->delete('Users','DATE_ADD(created,INTERVAL '.$this->CI->preference->item('account_activation_time').' DAY) <= NOW() AND active=0');

			return;
		}
        
        /**
         * Check a user is logged in
         *
         * @access public
         * @return boolean
         */
        function is_user()
        {
            $CI = &get_instance();
         
             if($CI->session)
             {
                $email = $CI->session->userdata('email');
                $group = $CI->session->userdata('group');
                
                if ($email !== FALSE && $group !== FALSE)
                {
                    // Logged in
                    log_message('debug','User is logged in');
                    return TRUE;
                }
             }
             
             // Not logged in
             log_message('debug','User is not logged in');
             return FALSE;
        }
        
		/**
		 * Check User Permissions
		 *
		 * Check the user has the correct permissions to access the resource
         * If $redirect is TRUE then redirect to login page, otherwise return boolean
		 *
		 * @access public
		 * @return boolean
		 */
		function check($resource,$action = NULL,$redirect = TRUE)
		{
            log_message('debug','Checking if user has access to "'.$resource.'"');
            if ( $this->CI->session ) {
                // Get details from user
                $email = $this->CI->session->userdata('email');
                $group = $this->CI->session->userdata('group');
                
                if ( $email !== FALSE && $group !== FALSE)
                {
                    // There user has a session with values
                    // Lets check there valid
                    if (kh_acl_check($group,$resource,$action))
                    {
                        // They can access this resource
                        log_message('debug','Yes they do have access');
                        return TRUE;
                    }
                }
            }
            
            // DENIED ACCESS
            log_message('debug','Access is denied for user');
            if ($redirect)
            {
                if( is_user())
                {
                    // They just don't have access
                    flashMsg('warning',$this->CI->lang->line('userlib_status_restricted_access'));
                    redirect('auth/login','location');
                }
                else
                {
                    // They arn't logged in
                    flashMsg('warning',$this->CI->lang->line('userlib_status_require_login'));
                     
                    // Save requested page
                    $this->CI->session->set_flashdata('requested_page',$this->CI->uri->uri_string());
                    redirect('auth/login','location');
                }
            }
            return FALSE;
		}
                     
      
        
        
        
        /**
         * Set User Login data
         *
         * When given a user ID it will fetch the required data
         * we need to save and save it to their session
         *
         * @access public
         * @param integer $user_id User ID of user
         * @return void
         */
        function set_userlogin($id)
        {
        	//@INFO: This dosn't seem very safe having this exposed to everything.
            // Create Users session data
            $user = $this->CI->user_model->getUsers(array('users.id'=>$id));
            $user = $user->row_array();
            $this->CI->session->set_userdata($user);

            if( !$this->CI->session ) {
                // Could not log user in, something went wrong
                flashMsg('error',$this->CI->lang->line('userlib_login_failed'));

                // Remove autologin value to stop an infinite loop
                delete_cookie('autologin');

                redirect('auth/login','location');
            }

            // Update users last login time
            $this->CI->user_model->updateUserLogin($id);
            return;
        }
	}
?>