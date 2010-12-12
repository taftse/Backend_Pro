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
 * The users controller performs the public user actions. Like login
 * logout activation etc.
 *
 * @subpackage      Users Module
 */
class Users extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->config('users', TRUE);

        // Load required classes
        $this->lang->load('users');
        $this->load->library('User');
        $this->load->library('form_validation');
        $this->load->model('user_model');

        log_message('debug', 'Users Controller loaded');
    }

    /**
     * Provide a form to allow the user to login to the system
     * 
     * @return void
     */
    public function login()
    {
        $this->redirect_if_logged_in();

        // Fetch the correct identity
        $mode = $this->setting->item('identity_mode');
        switch($mode)
        {
            case 'all':
                $data['identity'] = lang('users_username_or_email_label');
                break;
            
            default:
                $data['identity'] = lang('users_' . $mode . '_label');
        }

        // Setup the rules, we don't need strict rules this will be checked later
        $this->form_validation->set_rules('identity', $data['identity'], 'trim|required');
        $this->form_validation->set_rules('password', 'lang:users_password_label', 'trim|required');

        if($this->form_validation->run())
        {
            // Test the credentials given
            $identity = $this->input->post('identity');
            $password = $this->input->post('password');
            $remember = $this->input->post('remember_me') === 1;

            if($this->user->login($identity, $password, $remember))
            {
                $next_uri = $this->session->flashdata('next_uri');

                if($next_uri)
                {
                    // Redirect to the previous URL
                    redirect($next_uri, REDIRECT_METHOD);
                }
                else
                {
                    // Redirect to the default page
                    redirect($this->config->item('login_complete_redirect_uri', 'users'), REDIRECT_METHOD);
                }
            }
            else
            {
                $this->status->set('error', lang('users_invalid_login_credentials'));
            }
        }
        else
        {
            $this->status->set('error', $this->form_validation->_error_array);
        }

        // Renew the next_url
        $this->session->keep_flashdata('next_uri');

        $this->template->set_title(lang('users_login_title'));
        $this->template->set_breadcrumb(lang('users_login_title'), 'users/login');
        $this->template->build('public/login', $data);
    }

    /**
     * Log the user out of the system and redirect to the correct page
     * 
     * @return void
     */
    public function logout()
    {
        $this->user->logout();
        redirect($this->config->item('logout_complete_redirect_uri', 'users'), REDIRECT_METHOD);
    }

    /**
     * Activate a users account
     *
     * @param string $key Activation key
     * @return void
     */
    public function activate($key)
    {
        $this->redirect_if_logged_in();

        // Remove any inactive accounts which have expired
        $this->user_model->remove_inactive();

        // Get the user depending on the activation key given
        $user = $this->user_model->get_by_activation($key);

        if($user != false)
        {
            // Key is valid, make user active
            $data['is_active'] = 1;
            $data['activation_key'] = NULL;

            $this->user->save($data, array(), $user->id);
            $this->status->set('success', lang('users_account_activated'));
        }
        else
        {
            // There is a problem with the key
            $this->status->set('error', lang('users_activation_key_invalid'));
        }

        redirect('users/login', REDIRECT_METHOD);
    }

    /**
     * If the user is logged in already redirect them to the default
     * page
     * 
     * @return void
     */
    private function redirect_if_logged_in()
    {
        // Is the user already logged in?
        if($this->user->logged_in())
        {
            log_message('debug', 'User is already logged in, just redirect');
            redirect($this->config->item('login_complete_redirect_uri', 'users'), REDIRECT_METHOD);
        }
    }
}

/* End of file users.php */
/* Location: ./application/backendpro_modules/users/controllers/users.php */