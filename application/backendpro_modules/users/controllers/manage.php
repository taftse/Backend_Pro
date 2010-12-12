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
 * The Users manage controller allows users to be added/edited and deleted.
 *
 * @subpackage      Users Module
 */
class Manage extends Admin_Controller
{
    /**
     * An array containing all possible gender options
     * for the gender dropdown
     * 
     * @var array
     */
    private $gender_options = array();

    /**
     * The current user which is being edited. If no user is being edited this
     * is always false
     *
     * @var StdClass|false
     */
    private $current_user = false;

    /**
     * The current users profile which is being edited. If no user is being edited
     * this is always false
     *
     * @var StdClass|false
     */
    private $current_profile = false;

    public function __construct()
    {
        parent::__construct();

        $this->user->has_access('Users');

        // Load required files
        $this->lang->load('users');
        $this->lang->load('profile');
        $this->load->helper('form');
        $this->load->model('user_model');
        $this->load->model('user_profile_model');
        $this->load->library('form_validation');
        $this->load->model('access/group_model');

        // Add general values to the data array
        $this->template->data['user_groups'] = $this->build_group_list();
        // TODO: I don't like how this works, we should be able to pass global variables to partial views like we can to main views
        $this->gender_options = array('' => lang('profile_gender_unspecified'), 'male' => lang('profile_gender_male'), 'female' => lang('profile_gender_female'));

        $this->template->set_breadcrumb(lang('users_manage_title'), 'users/manage');
    }

    /**
     * Display a list of all users currently in the system
     * 
     * @return void
     */
    public function index()
    {
        $this->load->helper('date');        

        $data['users'] = $this->user_model->get_all();

        // Setup the template
        $this->template->set_title(lang('users_manage_title'));
        $this->template->build('admin/index', $data);
    }

    /**
     * Display a form to allow a new user to be added
     * 
     * @return void
     */
    public function add()
    {
        $this->user->has_access('Users', 'Add');

        // If the form was submitted, if so save it
        if($this->input->post('submit'))
        {
            $this->save();
        }

        // Get the empty objects for a user and profile
        $data['user'] = $this->user_model->get_object();
        $profile['profile'] = $this->user_profile_model->get_object();
        $profile['gender_options'] = $this->gender_options;       

        // Setup the template
        $this->template->set_partial('user_profile', 'admin/profile', $profile);
        $this->template->set_breadcrumb(lang('users_add_user_title'), 'users/add');
        $this->template->set_title(lang('users_add_user_title'));
        $this->template->build('admin/edit', $data);
    }

    /**
     * Dusplay a form to allow a users details to be edited
     * 
     * @param int $id User ID
     * @return void
     */
    public function edit($id)
    {
        $this->user->has_access('Users', 'Edit');

        // Get the current users details
        if(($this->current_user = $this->user_model->get($id)) == false)
        {
            log_message('error', 'Tried to edit an invalid user with id ' . $id);
            $this->status->set('error', lang('users_user_not_found'));
            redirect('users/manage', REDIRECT_METHOD);
        }
        else
        {
            // Also fetch the profile
            $this->current_profile = $this->user_profile_model->get($id);
        }
        
        // If the form was submitted, if so save it
        if($this->input->post('submit'))
        {
            $this->save();
        }

        // Get the user details
        $data['user'] = $this->current_user;
        $profile['profile'] = $this->current_profile;
        $profile['gender_options'] = $this->gender_options;  

        // Setup the template
        $title = sprintf(lang('users_edit_user_title'), $this->current_user->username);
        $this->template->set_partial('user_profile', 'admin/profile', $profile);
        $this->template->set_breadcrumb($title, 'users/edit/' . $id);
        $this->template->set_title($title);
        $this->template->build('admin/edit', $data);
    }

    /**
     * Save the user details from the form
     * 
     * @return void
     */
    public function save()
    {
        // Setup the validation rules for the form
        $this->set_validation_rules();

        if($this->form_validation->run($this))
        {
            // All the values passed, lets extract the values and save them
            list($user, $profile) = $this->extract_form_values();

            // Save the data
            if($this->current_user)
            {
                $this->user->save($user, $profile, $this->current_user->id);
            }
            else
            {
                $this->user->save($user, $profile);
            }

            $this->status->set('success', lang('users_changes_saved'));
            redirect('users/manage', REDIRECT_METHOD);
        }
        else
        {
            $this->status->set('error', $this->form_validation->_error_array);
        }
    }

    /**
     * Delete the current user specified
     *
     * @param int $id User ID
     * @return void
     */
    public function delete($id)
    {
        $this->user->has_access('Users', 'Delete');

        // Check the user doesn't match the current user
        if($this->user->data()->id == $id)
        {
            $this->status->set('error', lang('users_cannot_delete_yourself'));
            redirect('users/manage', REDIRECT_METHOD);
        }

        // Make sure the user exists
        if($this->user_model->get($id) == false)
        {
            log_message('error', 'Tried to delete an invalid user with id ' . $id);
            $this->status->set('error', lang('users_user_not_found'));
            redirect('users/manage', REDIRECT_METHOD);
        }

        // Everything is valid delete the user
        $this->user_model->delete($id);
        $this->status->set('success', lang('users_user_deleted'));
        log_message('debug', 'User deleted');

        redirect('users/manage', REDIRECT_METHOD);
    }

    /**
     * Set the validation rules
     *
     * @return void
     */
    private function set_validation_rules()
    {
        $this->load->helper('user_validation');

        // Load the user ID if we have it
        $user_id = ($this->current_user ? $this->current_user->id : '');

        // Setup the user validation rules
        $this->form_validation->set_rules('username', 'lang:users_username_label', get_username_rules());
        $this->form_validation->set_rules('email', 'lang:users_email_label', get_email_rules());
        $this->form_validation->set_rules('password', 'lang:users_password_label', get_password_rules($user_id));
        $this->form_validation->set_rules('confirm_password', 'lang:users_confirm_password_label', '');

        // Setup the profile validation rules
        $this->form_validation->set_rules('first_name', 'lang:profile_first_name_label', 'trim|max_length[64]');
        $this->form_validation->set_rules('second_name', 'lang:profile_second_name_label', 'trim|max_length[64]');
    }

    /**
     * Extract the form values from the page
     * 
     * @return array
     */
    private function extract_form_values()
    {
        // Extract the user fields
        $user['username'] = $this->input->post('username');
        $user['email'] = $this->input->post('email');
        $user['password'] = $this->input->post('password');
        $user['group_id'] = $this->input->post('group');
        $user['is_active'] = $this->input->post('is_active');

        // Extract the profile fields
        $profile['first_name'] = $this->input->post('first_name');
        $profile['second_name'] = $this->input->post('second_name');
        $profile['gender'] = $this->input->post('gender');

        // If we are editing a user and the password is blank remove it
        if($this->current_user && $user['password'] == '')
        {
            unset($user['password']);
        }

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
        if($this->current_user != false)
        {
            // If the username has not be changed then all is good
            if(!$this->user_model->is_dirty($this->current_user->id, 'username', $username))
            {
                return true;
            }
        }

        if(!$this->user_model->is_username_unique($username))
        {
            $this->form_validation->set_message('check_username_unique', lang('users_validation_username_check_unique'));
            return false;
        }

        return true;
    }

    /**
     * Check the email is unique ONLY on add
     *
     * @param string $email Entered email
     * @return bool
     */
    public function check_email_unique($email)
    {
        if($this->current_user != false)
        {
            // If the email has not be changed then all is good
            if(!$this->user_model->is_dirty($this->current_user->id, 'email', $email))
            {
                return true;
            }
        }

        if(!$this->user_model->is_email_unique($email))
        {
            $this->form_validation->set_message('check_email_unique', lang('users_validation_email_check_unique'));
            return false;
        }

        return true;
    }
}
 
/* End of manage.php */
/* Location: ./application/backendpro_modules/users/controllers/manage.php */