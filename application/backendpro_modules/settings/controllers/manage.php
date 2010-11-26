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
 * The manage controller allows all the system settings to be edited/deleted. As
 * long as the user has the right access permissions.
 *
 * @subpackage      Settings Module
 */
class Manage extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Check the user has access
        $this->user->has_access('Settings', 'Manage');

        $this->load->model('setting_model');
        $this->load->helper('form');
        $this->lang->load('settings');
        $this->load->library('form_validation');

        $this->template->set_breadcrumb(lang('settings'), 'settings');

        // Use the side column to display help messages
        $this->template->layout = 'admin/master_side_column';
        $this->template->set_partial('side_column', 'admin/manage_help');
    }

    /**
     * Add a new setting
     *
     * @return void
     */
    public function add()
    {
        $data['setting'] = $this->setting_model->get_object();
        
        // Get the allowed setting types
        $data['types'] = $this->setting_model->get_types();
        $data['types'] = array_combine($data['types'],$data['types']);

        // Since there is no slug for the setting we are editing, set it blank
        $data['original_slug'] = '';

        $this->template->set_title(lang('add_setting'));
        $this->template->set_breadcrumb(lang('add_setting'), 'settings/add');
        $this->template->build('admin/edit', $data);
    }

    /**
     * Edit a current setting
     *
     * @param string $slug Setting slug
     * @return void
     */
    public function edit($slug)
    {
        // Get the setting details
        $data['setting'] = $this->setting_model->get($slug);

        // Get the allowed setting types
        $data['types'] = $this->setting_model->get_types();
        $data['types'] = array_combine($data['types'],$data['types']);

        $data['original_slug'] = $slug;

        $this->template->set_title(lang('edit_setting'));
        $this->template->set_breadcrumb(lang('edit_setting'), 'settings/edit/' . $slug);
        $this->template->build('admin/edit', $data);
    }

    /**
     * Save the submitted edit form
     *
     * @return void
     */
    public function save()
    {
        $add_new = $this->input->post('original_slug') == '';
        
        // Set the validation rules
        $this->form_validation->set_rules('slug', 'lang:slug', 'trim|required|alpha_dash|max_length[32]|min_length[5]');
        $this->form_validation->set_rules('title', 'lang:title', 'trim|required|max_length[32]|min_length[5]');
        $this->form_validation->set_rules('description', 'lang:description', 'trim|max_length[255]');
        $this->form_validation->set_rules('type', 'lang:type', 'trim|required');
        $this->form_validation->set_rules('value', 'lang:value', 'trim|max_length[255]');
        $this->form_validation->set_rules('options', 'lang:options', 'trim|max_length[255]|callback_options_check');
        $this->form_validation->set_rules('module', 'lang:module', 'trim|max_length[32]');

        if($this->form_validation->run($this))
        {
            // Everything is valid, lets save it back to the database
            $values = $this->extract_form_values();

            if($add_new):
                $this->setting_model->insert($values);
            else:
                $this->setting_model->update($this->input->post('original_slug'), $values);
            endif;

            $this->status->set('success', lang('settings_saved'));
            redirect('settings', REDIRECT_METHOD);
        }
        else
        {
            $this->status->set('error',$this->form_validation->_error_array);
        }

        if($add_new):
            $this->add();
        else:
            $this->edit($this->input->post('original_slug'));
        endif;
    }

    /**
     * Delete a setting
     *
     * @param string $slug Slug setting
     * @return void
     */
    public function delete($slug)
    {
        $this->setting_model->delete($slug);
    }

    /**
     * Extract all values from the form fields
     * 
     * @return array
     */
    private function extract_form_values()
    {
        $data = array();

        $data['slug'] = $this->input->post('slug');
        $data['title'] = $this->input->post('title');
        $data['description'] = $this->input->post('description');
        $data['type'] = $this->input->post('type');
        $data['value'] = $this->input->post('value');
        $data['options'] = $this->input->post('options');
        $data['validation_rules'] = $this->input->post('validation_rules');
        $data['module'] = $this->input->post('module');
        $data['is_required'] = $this->input->post('is_required');
        $data['is_gui'] = $this->input->post('is_gui');

        return $data;
    }

    /**
     * Check the options list is comma seperated
     *
     * @param string $value Value to check
     * @return bool
     */
    function options_check($value)
    {
        $type = $this->input->post('type');

        if($type == 'select' || $type == 'select-multiple')
        {
            if(count($value) == 0)
            {
                $this->form_validation->set_message('options_check', lang('validation_options_check_required'));
                return FALSE;
            }

            // Check at least 1 option exists
            if(!preg_match("/^[^,]+(,[^,]+)*$/", $value))
            {
                $this->form_validation->set_message('options_check', lang('validation_options_check_invalid'));
                return FALSE;
            }
        }

        return TRUE;
    }
}
 
/* End of manage.php */
/* Location: ./application/backendpro_modules/settings/controllers/manage.php */