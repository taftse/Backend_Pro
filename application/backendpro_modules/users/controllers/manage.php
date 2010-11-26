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

class Manage extends Admin_Controller
{
    private $gender_options = array();

    public function __construct()
    {
        parent::__construct();

        $this->user->has_access('Users');

        // Load required files
        $this->lang->load('users');
        $this->load->helper('form');
        $this->load->model('user_model');
        $this->load->model('user_profile_model');
        $this->load->library('form_validation');
        $this->load->model('access/group_model');

        // Add general values to the data array
        $this->template->data['groups'] = $this->build_group_list();        
        $this->gender_options = array('' => lang('gender_unspecified'), 'male' => lang('gender_male'), 'female' => lang('gender_female'));

        $this->template->set_breadcrumb(lang('manage_users'), 'users/manage');
    }

    public function index()
    {
        $this->load->helper('date');        

        $data['users'] = $this->user_model->get_all();

        // Setup the template
        $this->template->set_title(lang('manage_users'));
        $this->template->build('admin/index', $data);
    }

    public function add()
    {
        $this->user->has_access('Users', 'Add');
        
        // Get empty objects
        $data['user'] = $this->user_model->get_object();
        $profile['profile'] = $this->user_profile_model->get_object();
        $profile['gender_options'] = $this->gender_options;       

        // Setup the template
        $this->template->set_partial('user_profile', 'admin/profile', $profile);
        $this->template->set_breadcrumb(lang('add_user'), 'users/add');
        $this->template->set_title(lang('add_user'));
        $this->template->build('admin/edit', $data);
    }

    public function edit($id)
    {
        $this->user->has_access('Users', 'Edit');

        // Get the user details
        $data['user'] = $this->user_model->get($id);
        $profile['profile'] = $this->user_profile_model->get($id);
        $profile['gender_options'] = $this->gender_options;  

        // Setup the template
        $this->template->set_partial('user_profile', 'admin/profile', $profile);
        $this->template->set_breadcrumb(lang('edit_user'), 'users/edit/' . $id);
        $this->template->set_title(lang('edit_user'));
        $this->template->build('admin/edit', $data);
    }

    public function save()
    {
        $id = $this->input->post('user_id');

        if($id == '')
            $this->user->has_access('Users', 'Add');
        else
            $this->user->has_access('Users', 'Edit');

        $this->set_validation_rules($id);

        if($this->form_validation->run($this))
        {
            list($user, $profile) = $this->extract_form_values();

            $this->user->save($user, $profile, $id);

            $this->status->set('success', lang('user_saved'));
            redirect('users/manage', REDIRECT_METHOD);
        }
        else
        {
            $this->status->set('error', $this->form_validation->_error_array);
        }

        if($id == ''):
            $this->add();
        else:
            $this->edit($this->input->post('user_id'));
        endif;
    }

    public function delete($id)
    {
        $this->user->has_access('Users', 'Delete');
        
        print "Delete";
    }

    /**
     * Set the validation rules
     *
     * @param int $user_id User ID
     * @return void
     */
    private function set_validation_rules($user_id)
    {
        $this->load->helper('user_validation');
        
        // Setup the user validation rules
        $this->form_validation->set_rules('username', 'lang:username', get_username_rules());
        $this->form_validation->set_rules('email', 'lang:email', get_email_rules());
        $this->form_validation->set_rules('password', 'lang:password', get_password_rules($user_id));
        $this->form_validation->set_rules('confirm_password', 'lang:confirm_password', '');

        // Setup the profile validation rules
        $this->form_validation->set_rules('first_name', 'lang:first_name', 'trim|max_length[64]');
        $this->form_validation->set_rules('second_name', 'lang:second_name', 'trim|max_length[64]');
    }

    /**
     * Extract the form values from the page
     * 
     * @return array
     */
    private function extract_form_values()
    {
        $user = array();
        $profile = array();

        $user['username'] = $this->input->post('username');
        $user['email'] = $this->input->post('email');

        $user['password'] = $this->input->post('password');
        if($user['password'] == '')
        {
            unset($user['password']);
        }

        $user['group_id'] = $this->input->post('group');
        $user['is_active'] = $this->input->post('is_active');

        $profile['first_name'] = $this->input->post('first_name');
        $profile['second_name'] = $this->input->post('second_name');
        $profile['gender'] = $this->input->post('gender');

        return array($user, $profile);
    }

    /**
     * Build a simple array containing the group ID and name
     * 
     * @return array
     */
    private function build_group_list()
    {
        $groups = $this->group_model->get_all();

        $list = array();

        foreach($groups as $group)
        {
            $list[$group->id] = $group->name;
        }

        return $list;
    }

    /**
     * Check the username is unique ONLY on add
     *
     * @param string $username Entered username
     * @return bool
     */
    public function check_username_unique($username)
    {
        $id = $this->input->post('user_id');
        if($id != '')
        {
            // If the username has not be changed then all is good
            if(!$this->user_model->is_dirty($id, 'username', $username))
            {
                return TRUE;
            }
        }

        if(!$this->user_model->is_username_unique($username))
        {
            $this->form_validation->set_message('check_username_unique', lang('validation_username_check_unique'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Check the email is unique ONLY on add
     *
     * @param string $email Entered email
     * @return bool
     */
    public function check_email_unique($email)
    {
        $id = $this->input->post('user_id');
        if($id != '')
        {
            // If the email has not be changed then all is good
            if(!$this->user_model->is_dirty($id, 'email', $email))
            {
                return TRUE;
            }
        }

        if(!$this->user_model->is_email_unique($email))
        {
            $this->form_validation->set_message('check_email_unique', lang('validation_email_check_unique'));
            return FALSE;
        }

        return TRUE;
    }
}
 
/* End of manage.php */
/* Location: ./application/backendpro_modules/users/controllers/manage.php */