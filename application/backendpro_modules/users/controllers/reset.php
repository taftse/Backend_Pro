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
 * Controls all user password reset functionality
 *
 * @subpackage      Users Module
 */
class Reset extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->helper('user_validation');

        $this->template->set_breadcrumb(lang('users_request_reset_title'), 'users/reset/request');

        log_message('debug', 'Users Reset Controller loaded');
    }

    /**
     * Allow the user to specify a new password for their account
     * 
     * @return void
     */
    public function index()
    {
        $this->redirect_if_logged_in();
        
        // Try to get the matching user
        $key = $this->uri->segment(3);
        $user = $this->user_model->get_by_reset($key);

        if($user == false)
        {
            // Activation key is invalid
            $this->status->set('warning', lang('users_reset_key_invalid'));
            redirect('users/reset/request', REDIRECT_METHOD);
        }
        else
        {
            // Validate input
            $this->form_validation->set_rules('new_password', 'lang:users_new_password_label', get_password_rules());
            $this->form_validation->set_rules('confirm_password', 'lang:users_confirm_password_label', 'trim');

            if($this->form_validation->run())
            {
                // Save the new password
                $data['password'] = $this->input->post('new_password');
                $data['reset_key'] = NULL;

                $this->user->save($data, array(), $user->id);
                $this->status->set('success', lang('users_password_reset_saved'));
                redirect('users/login', REDIRECT_METHOD);
            }
            else
            {
                $this->status->set('error', $this->form_validation->_error_array);
            }
        }

        $this->template->set_title(lang('users_reset_title'));
        $this->template->set_breadcrumb(lang('users_reset_title'), 'users/reset');
        $this->template->build('public/password_reset', array('user' => $user));
    }

    /**
     * Allow the user to request that their password is reset
     * 
     * @return void
     */
    public function request()
    {
        $this->redirect_if_logged_in();

        // Set validation rules
        $this->form_validation->set_rules('email', lang('users_email_label'), 'trim|required|valid_email');

        if($this->form_validation->run())
        {
            $this->user->request_reset($this->input->post('email'));
            $this->status->set('success', lang('users_password_reset_sent'));
        }
        else
        {
            $this->status->set('error', $this->form_validation->_error_array);
        }

        $this->template->set_title(lang('users_request_reset_title'));
        $this->template->build('public/request_password_reset');
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
 
/* End of reset.php */
/* Location: ./application/backendpro_modules/users/controllers/reset.php */