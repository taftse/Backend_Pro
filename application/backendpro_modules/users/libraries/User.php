<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 5.2.6 or newer
 *
 * @package         BackendPro
 * @author          Adam Price <adam@kaydoo.co.uk>
 * @copyright       2008-2010, Adam Price
 * @license         http://www.opensource.org/licenses/mit-license.php MIT
 * @license         http://www.gnu.org/licenses/gpl.html GPL
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

/**
 * The user library provides functions to interact with the current user or log
 * the user into the system
 *
 * @subpackage      Users Module
 */
class User
{
    private $_data = false;
    
    public function __construct()
    {
        $CI = &get_instance();

        // Load required module files
        $CI->lang->load('users/users');
        $CI->load->model('users/user_model');
        $CI->load->model('users/user_profile_model');
        $CI->load->model('users/authentication_model');
        $CI->load->model('access/access_model');
        $CI->load->library('users/user_email');

        log_message('debug', 'User Library loaded');
    }

    /**
     * Fetch the current users data including custom profile
     * values.
     * 
     * @return object|bool Returns FALSE if user is not logged in
     */
    public function data()
    {
        if($this->logged_in())
        {
            if($this->_data !== false)
            {
                log_message('debug', 'User data has been cached, returning cached copy');
                return $this->_data;
            }

            log_message('debug','Fetching the current user data');
            $CI = &get_instance();

            $id = $CI->session->userdata('user_id');

            // Fetch the data
            $user = $CI->user_model->get($id);
            $user->profile = $CI->user_profile_model->get($id);
            unset($user->profile->user_id);

            // Fetch the user profile values            
            log_message('debug', 'User data loaded and saved to session');

            $this->_data = $user;
            return $this->_data;
        }
        else
        {
            log_message('debug','User is not logged in, no user data to load');
            return false;
        }
    }

    /**
     * Check if a user is logged into the system
     *
     * @param bool $redirect Whether to redirect to the login page if the user isn't logged in
     * @return bool
     */
    public function logged_in($redirect = false)
    {
        $CI = &get_instance();

        $id = $CI->session->userdata('user_id');
        
        $logged_in = !empty($id);
    
        if(!$logged_in && $redirect)
        {
            // Save the current uri so we can redirect back
            $CI->session->set_flashdata('next_uri', uri_string());

            // Redirect to the login page
            $CI->status->set('warning', lang('users_login_required'));
            redirect('users/login', REDIRECT_METHOD);
        }

        return $logged_in;
    }

    /**
     * Attempt to log the user into the system
     *
     * @param string $identity Users identity
     * @param string $password Users Password
     * @param bool $remember
     * @return bool
     */
    public function login($identity, $password, $remember = FALSE)
    {
        $CI = &get_instance();

        if($CI->authentication_model->login($identity, $password, $remember))
        {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Log the user out from the system
     * 
     * @return bool
     */
    public function logout()
    {
        log_message('debug','Logging the user out of the system');
        $CI = &get_instance();

        $CI->load->helper('cookie');

        $CI->session->unset_userdata('id');
        $CI->session->unset_userdata('user_id');
        $CI->session->unset_userdata('group');
        $CI->session->unset_userdata('identity');
        $CI->session->unset_userdata('identity_mode');

        // Delete the remember me cookie if it exists
		if (get_cookie('remember_me'))
	    {
            delete_cookie('remember_me');
	    }

		$CI->session->sess_destroy();

        // Reset userdata
        $this->_data = array();

        log_message('debug','Logout complete');
		return true;
    }

    /**
     * Generates a reset request key for the given email. Sends the users an
     * email informing them of the reset
     *
     * @param string $email Email to send the reset request to
     * @return void
     */
    public function request_reset($email)
    {
        $CI = &get_instance();

        // Get the user if we can
        $user = $CI->user_model->get_by_email($email);

        if(empty($user))
        {
            // Email isn't in use
            log_message('debug', 'Email is not in use, sending email informing the owner');
            $CI->user_email->send($email, lang('users_email_subject_reset_password'), 'users/email/reset_no_account');
        }
        else
        {
            // Email exists, mark account for reset
            log_message('debug', 'Email found, setting a reset key');
            $reset_key = md5($email . time());
            $CI->user_model->update($user->id, array('reset_key' => $reset_key));

            $CI->user_email->send($email, lang('users_email_subject_reset_password'), 'users/email/reset_account', array('user' => $user, 'reset_key' => $reset_key));
        }
    }

    /**
     * Check the user has access to a given resource and optional action
     *
     * @param string $resource Resource name
     * @param string $action Action name
     * @param bool $redirect Whether to redirect the user if they don't have access
     * @return bool
     */
    public function has_access($resource, $action = null, $redirect = true)
    {
        $CI =& get_instance();

        if($this->logged_in($redirect))
        {
            // Get the current users Group ID
            $group_id = $CI->session->userdata('group_id');

            if($CI->access_model->has_access($group_id, $resource, $action))
            {
               return true;
            }
            else
            {
                // Redirect to last page
                if($redirect)
                {
                    $previous_uri =  $CI->session->flashdata('previous_uri');

                    // If there is no previous (i.e. we came from an outside site, send to the home page)
                    $previous_uri = ($previous_uri) ? $previous_uri : '';

                    $CI->status->set('error', lang('users_access_denied'));
                    redirect($previous_uri, REDIRECT_METHOD);
                }
            }
        }

        return false;
    }

    /**
     * Save user data into the system. Sends the user the required emails
     * when certain events are triggered.
     *
     * @param array $user User data
     * @param array $profile Profile data
     * @param int $id User ID
     * @return void
     */
    public function save(array $user, array $profile = array(), $id = '')
    {
        $CI =& get_instance();
        $current_user = (is_numeric($id) ? $CI->user_model->get($id) : false);

        // If there is a password
        if(isset($user['password']))
        {
            $password = $user['password'];

            // Hash the password
            $user['password'] = $CI->authentication_model->hash_password($password);

            // If we are updating an existing user
            if($current_user)
            {
                // If the password has changed
                if($user['password'] != $current_user->password)
                {
                    // Send an email to the user informing them of the changed password
                    $data = array('username' => $current_user->username, 'password' => $password);
                    $CI->user_email->send($current_user->id, lang('users_email_subject_password_change'), 'users/email/password_changed', $data);
                }
            }
        }

        // If there is an activity value
        if(isset($user['is_active']))
        {
            // If we are adding a new user
            if(!$current_user)
            {                
                if(!$user['is_active'])
                {
                    // If the user requires activation, create key
                    $user['activation_key'] = md5($user['email'] . time());
                }

                // Send a new account email
                $data = array('user' => $user, 'password' => $password);
                $CI->user_email->send($user['email'], lang('users_email_subject_new_account'), 'users/email/new_account', $data);
            }
        }

        $CI->db->trans_start();
        if($current_user)
        {
            // Update the current users details
            $CI->user_model->update($id, $user);

            if(!empty($profile))
            {
                $CI->user_profile_model->update($id, $profile);
            }
        }
        else
        {
            // Add a new user
            $profile['user_id'] = $CI->user_model->insert($user);

            if(!empty($profile))
            {
                $CI->user_profile_model->insert($profile);
            }
        }
        $CI->db->trans_complete();
    }
}

/* End of file User.php */
/* Location: ./application/backendpro_modules/users/libraries/User.php */