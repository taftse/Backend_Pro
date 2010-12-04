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
 * The authentication_model provides methods for a user to login, logout,
 * register etc with the system.
 */
class Authentication_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('cookie');
        $this->load->model('users/user_model');

        log_message('debug', 'Authentication_model loaded');
    }

    /**
     * Login a user to the system. Returns TRUE or FALSE if login
     * was completed
     *
     * @param  $identity
     * @param  $password
     * @return bool
     */
    public function login($identity, $password, $remember = false)
    {
        if(empty($identity) || empty($password))
        {
            log_message('error','Cannot log user in, no details provided');
            return FALSE;
        }

        log_message('debug','Logging the user ' . $identity . ' into the system');

        // Try to find the user depending on the identity used
        $identity_mode = $this->setting->item('identity_mode');
        $user = $this->user_model->get_user_for_login($identity, $identity_mode);

        if($user !== FALSE)
        {
            // re-hash the password
            $password = $this->hash_password($password);

            if($password === $user->password)
            {
                log_message('debug','Password match, logging user into system');

                $this->update_last_login($user->id);

                // Save the users details to a new session
                $this->session->set_userdata('user_id', $user->id);
                $this->session->set_userdata('group_id', $user->group_id);
                $this->session->set_userdata('identity', $identity);
                $this->session->set_userdata('identity_mode', $identity_mode);

                // Get the correct group name
                //$group_result = $this->db->select('name')->where('id', $user->group_id)->get($this->tables['groups'])->row();
                //$this->session->set_userdata('group', $group_result->name);

                // If they want to remember their login do so
                if($remember)
                {
                    $this->remember_user($user->id);
                }

                log_message('debug','User credentials correct for ' . $identity . ', user is now logged in');
                return TRUE;
            }
            else
            {
                log_message('debug','Passwords do not match');
            }
        }

        log_message('debug','User credentials incorrect for ' . $identity . ', login aborted');
        return FALSE;
    }

    /**
     * Create a remember me cookie for the given users
     * 
     * @param  $id
     * @return bool
     */
    private function remember_user($id)
    {
        if(!$id)
        {
            throw new BackendProException('Cannot remember the users login, no user Id given');
        }

        // If the process fails, no big shakes its not major
        try
        {
            $user = $this->user_model->get($id);
            log_message('debug', 'Setting up a remember cookie for user: ' . $user->username);

            // Generate a unique remember code
            $code = sha1($user->password . time());

            // Save it to the database
            $this->user_model->update($id, array('remember_code' => $code));

            // Set the cookie
            $expire_time = $this->setting->item('auto_login_length');
            set_cookie('remember_me', $code, time() + $expire_time);
            log_message('debug','Remember me cookie created');
        }
        catch (BackendProException $ex)
        {
            log_message('error','Unable to add cookie the remember cookie');
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Hash the password using the system salt
     *
     * @param  $password
     * @return bool|string
     */
    public function hash_password($password)
    {
        if(empty($password))
        {
            log_message('error','Cannot hash the password, it is blank');
            return FALSE;
        }

        // Get the system salt
        $salt = $this->salt();

        if(empty($salt))
        {
            throw new BackendProException('Cannot hash the password, no salt has been given');
        }

        // Otherwise if everything when ok, sha1 the salt and password
        return sha1($password . $salt);
    }

    /**
     * Get the system salt
     * 
     * @return string
     */
    private function salt()
    {        
        return $this->config->item('encryption_key');
    }

    /**
     * Update the users last login datetime
     * 
     * @param int $id
     * @return bool
     */
    private function update_last_login($id)
    {
        if(!$id)
        {
            throw new BackendProException('Cannot update last login time, no users Id given');
        }

        log_message('debug','Updating users last login details');
        return $this->user_model->update_last_login($id);
    }
}

/* End of file authentication_model.php */
/* Location: ./application/backendpro_modules/users/models/authentication_model.php */