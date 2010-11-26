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

class Users extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->config('users', TRUE);

        // Load required classes
        $this->load->library('User');
        $this->load->library('form_validation');

        log_message('debug', 'Users Controller loaded');
    }

    /**
     * Provide a form to allow the user to login to the system
     * 
     * @return void
     */
    public function login()
    {       
        // Fetch the correct identity
        $mode = $this->setting->item('identity_mode');
        switch($mode)
        {
            case 'all':
                $data['identity'] = lang('username_or_email');
                break;
            
            default:
                $data['identity'] = lang($mode);
        }

        // Setup the rules, we don't need strict rules this will be checked later
        $this->form_validation->set_rules('identity', $data['identity'], 'trim|required');
        $this->form_validation->set_rules('password', 'lang:password', 'trim|required');

        if($this->form_validation->run())
        {
            // Test the credentials given
            $identity = $this->input->post('identity');
            $password = $this->input->post('password');
            $remember = $this->input->post('remember_me') === 1;

            if($this->user->login($identity, $password, $remember))
            {
                // BUG: This dosn't seem to be working
                $next_uri = $this->session->flashdata('next_uri');

                if($next_uri)
                {
                    // Redirect to the previous URL
                    redirect($next_uri, REDIRECT_METHOD);
                }
                else
                {
                    // Redirect to the default page
                    redirect($this->config->item('login_comlete_redirect_uri', 'users'), REDIRECT_METHOD);
                }
            }
            else
            {
                $this->status->set('error', lang('invalid_login_credentials'));
            }
        }
        else
        {
            $this->status->set('error', $this->form_validation->_error_array);
        }

        // Renew the next_url
        $this->session->keep_flashdata('next_url');

        $this->template->set_title(lang('login'));
        $this->template->set_breadcrumb(lang('login'), 'users/login');
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
        $this->user_model->remove_inactive();

        $user = $this->user_model->get_by_activation($key);

        if(!empty($user))
        {
            // Key is valid, make user active
            $data['is_active'] = 1;
            $data['activation_key'] = NULL;

            $this->user->save($data, array(), $user->id);
            $this->status->set('success', lang('account_activated'));
        }
        else
        {
            // There is a problem with the key
            $this->status->set('error', lang('activation_key_invalid'));
        }

        redirect('users/login', REDIRECT_METHOD);
    }
}

/* End of file users.php */
/* Location: ./application/backendpro_modules/users/controllers/users.php */