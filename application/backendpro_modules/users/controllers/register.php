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
 * The user register controller allows a new user to register a new account
 * in the system
 */
class Register extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->config('users', TRUE);
        $this->load->helper('user_validation');
        $this->load->library('form_validation');
        $this->load->model('user_model');

        // If registration has been turned off redirect the user away
        if(!$this->setting->item('allow_user_registration'))
        {
            $this->status->set('error', lang('registration_disabled'));
            redirect($this->config->item('registration_disabled_redirect_uri', 'users'), REDIRECT_METHOD);
        }

        log_message('debug', 'User Register Controller loaded');
    }

    /**
     * Display a registration form along with checking the data entered
     * 
     * @return void
     */
    public function index()
    {
        $this->set_validation_rules();

        if($this->form_validation->run($this))
        {
            // Extract form fields
            list($user, $profile) = $this->extract_form_fields();

            // Save the user
            $this->user->save($user, $profile);

            $this->status->set('success', lang('new_account_created'));
            redirect($this->config->item('registration_complete_redirect_uri', 'users'), REDIRECT_MODE);
        }
        else
        {
            $this->status->set('error', $this->form_validation->_error_array);
        }

        $this->template->set_title(lang('create_account'));
        $this->template->set_breadcrumb(lang('create_account'), 'users/register');
        $this->template->build('public/register');
    }

    /**
     * Check the email is unqiue in the system
     *
     * @param string $email Entered email
     * @return bool
     */
    public function check_email_unique($email)
    {
        $this->form_validation->set_message('check_email_unique', lang('validation_email_check_unique'));
        return $this->user_model->is_email_unique($email);
    }

    /**
     * Check the username is unique in the system
     *
     * @param string $username Entered username
     * @return bool
     */
    public function check_username_unique($username)
    {
        $this->form_validation->set_message('check_username_unique', lang('validation_username_check_unique'));
        return $this->user_model->is_username_unique($username);
    }

    /**
     * Extract the form values into data arrays
     * 
     * @return array
     */
    private function extract_form_fields()
    {
        // Extract the core values
        $user['username'] = $this->input->post('username');
        $user['email'] = $this->input->post('email');
        $user['password'] = $this->input->post('password'); // Hashing is done on save
        $user['is_active'] = 0; // We don't need to generate activation key, this is done on save
        $user['group_id'] = $this->setting->item('default_user_group');

        // If you are collecting special profile values from the registration process
        // add the respective code here
        $profile = array();

        return array($user, $profile);
    }

    /**
     * Setup the register validation rules
     *
     * @return void
     */
    private function set_validation_rules()
    {
        // Setup the core rules
        $this->form_validation->set_rules('username', 'lang:username', get_username_rules());
        $this->form_validation->set_rules('email', 'lang:email', get_email_rules(TRUE));
        $this->form_validation->set_rules('confirm_email', 'lang:confirm_email', '');
        $this->form_validation->set_rules('password', 'lang:password', get_password_rules());
        $this->form_validation->set_rules('confirm_password', 'lang:confirm_password', '');

        // If you are collecting special profile values from the registration process
        // add their respective validation rules here
    }
}
 
/* End of register.php */
/* Location: ./application/backendpro_modules/users/controllers/register.php */