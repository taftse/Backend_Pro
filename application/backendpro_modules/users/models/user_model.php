<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 5.2.6 or newer
 *
 * @package         BackendPro
 * @author          Adam Price <adam@kaydoo.co.uk>
 * @copyright       2008-2010, Adam Price
 * @license			http://www.opensource.org/licenses/mit-license.php MIT
 * @license         http://www.gnu.org/licenses/gpl.html GPL
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

/**
 * Provides functionality to get/insert/update/delete
 * user rows from the database.
 *
 * @subpackage      Users Module
 */
class User_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();

        $tables = $this->config->item('tables','backendpro');

        // Set base model properties
        $this->table = $tables['users'];
        $this->set_created_date = TRUE;
        $this->set_modified_date = TRUE;

        log_message('debug','User_model loaded');
    }

    /**
     * Returns an empty user object
     *
     * @return StdClass
     */
    public function get_object()
    {
        $user = new StdClass();

        $user->id = NULL;
        $user->username = NULL;
        $user->email = NULL;
        $user->group = NULL;
        $user->is_active = 1;

        return $user;
    }

    /**
     * Get a user by their email address
     *
     * @param string $email Email address
     * @return object User object
     */
    public function get_by_email($email)
    {
        return parent::get_by('email', $email);
    }

    /**
     * Gets a user by their activation key
     *
     * @param $string $key Activation key
     * @return object User object
     */
    public function get_by_activation($key)
    {
        return parent::get_by('activation_key', $key);
    }

    /**
     * Gets a user by their reset key
     *
     * @param string $key Reset key
     * @return object User object
     */
    public function get_by_reset($key)
    {
        return parent::get_by('reset_key', $key);
    }

    /**
     * Get user details from those given at login
     *
     * @param string $identity The users entered identity
     * @param string $identity_mode The current identity mode the system accepts
     * @return bool
     */
    public function get_user_for_login($identity, $identity_mode)
    {
        if($identity_mode == 'all')
        {
            $this->db->where('( 1=', '1', false); // add a bracket open
            $this->db->where('username', $identity);
            $this->db->or_where('email', $identity);
            $this->db->where('1', '1)', false); // add a bracket close 
        }
        else
        {
            $this->db->where($identity_mode, $identity);
        }

        // Make sure the user is active
        $this->db->where('is_active', 1);

        $result = $this->db->get($this->table);

        if($result === FALSE)
        {
            throw new DatabaseException('Unable to get user from login details');
        }
        else
        {
            if($result->num_rows() == 1)
            {
                // If we have a user return them
                log_message('debug','User found matching the login details');
                return $result->row();
            }
            else
            {
                // Otherwise return FALSE
                log_message('debug','No user found which matches the login details entered');
                return FALSE;
            }
        }
    }

    /**
     * Update the last login time of the user and the last IP
     *
     * @param int $id The users Id
     * @return bool
     */
    public function update_last_login($id)
    {
        $this->load->helper('date');

        $time = unix_to_human(now(), TRUE, 'eu');
        $ip = $this->input->ip_address();

        $this->set_modified_date = FALSE;
        $result = $this->update($id, array('last_login'=> $time, 'last_ip' => $ip));
        $this->set_modified_date = TRUE;
        return $result;
    }

    /**
     * Remove all inactive user accounts from the system which have not
     * been activated within the activation period
     *
     * @return void
     */
    public function remove_inactive()
    {
        $now = time();
        $this->load->helper('date');

        // Get the activation period in DAYS
        $period = $this->setting->item('activation_period');
        $period *= 24; // HOURS
        $period *= 60; // MINS
        $period *= 60; // SECONDS

        // Get expire time
        $expire = $now - $period;

        $where = array(
            'created_on <= ' => unix_to_human($expire, TRUE),
            'is_active' => 0);

        $this->delete_by($where);
    }

    /**
     * Check if the username is unique
     *
     * @param string $username Username
     * @return bool
     */
    public function is_username_unique($username)
    {
        $user = parent::get_by('username', $username);
        return empty($user);
    }

    /**
     * Check if the email is unqiue
     *
     * @param string $email Email
     * @return bool
     */
    public function is_email_unique($email)
    {
        $user = $this->get_by_email($email);
        return empty($user);
    }

    /**
     * Checks if the property for a user is clean or dirty
     *
     * @param int $id User ID
     * @param string $property Property name
     * @param mixed $new_value New value
     * @return bool
     */
    public function is_dirty($id, $property, $new_value)
    {
        $user = parent::get_by(array($this->primary_key => $id, $property => $new_value));
        return empty($user);
    }
}

/* End of file user_model.php */
/* Location: ./application/backendpro_modules/users/models/user_model.php */