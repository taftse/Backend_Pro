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
 * Provides functionality to get/insert/update/delete user profiles
 * from he database
 */
class User_profile_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('users/user_model');

        $tables = $this->config->item('tables','backendpro');

        // Set base model properties
        $this->table = $tables['user_profiles'];
        $this->primary_key = 'user_id';

        log_message('debug','User_profile_model loaded');
        // TODO: When you update a users profile it should update the users modified date
    }

    /**
     * Returns an empty instance of the profile object
     * 
     * @return StdClass
     */
    public function get_object()
    {
        $profile = new StdClass();

        $profile->first_name = NULL;
        $profile->second_name = NULL;
        $profile->gender = NULL;

        return $profile;
    }
}
 
/* End of user_profile_model.php */
/* Location: ./application/backendpro_modules/users/models/user_profile_model.php */