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
    /**
     * Holds the current setting being edited. If adding a setting
     * this is set to false
     * 
     * @var StdClass|false
     */
    private $current_setting = false;

    public function __construct()
    {
        parent::__construct();

        // Check the user has access
        $this->user->has_access('Settings', 'Manage');

        $this->load->model('setting_model');
        $this->load->helper('form');
        $this->lang->load('settings');
        $this->load->config('settings', true);
        $this->load->library('form_validation');

        $this->template->set_breadcrumb(lang('settings_title'), 'settings');

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
        // If the form was submitted save it
        if($this->input->post('submit'))
        {
            $this->save();
        }

        // Fetch an empty setting object
        $data['setting'] = $this->setting_model->get_object();
        
        // Get the allowed setting types
        $data['types'] = $this->config->item('control_types', 'settings');
        $data['types'] = array_combine($data['types'],$data['types']);

        $this->template->set_title(lang('settings_add_setting_title'));
        $this->template->set_breadcrumb(lang('settings_add_setting_title'), 'settings/add');
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
        if(($this->current_setting = $this->setting_model->get($slug)) == false)
        {
            $this->status->set('error', lang('settings_setting_not_found'));
            redirect('settings', REDIRECT_METHOD);
        }

        // If the form was submitted save it
        if($this->input->post('submit'))
        {
            $this->save();
        }

        // Get the allowed setting types
        $data['types'] = $this->config->item('control_types', 'settings');
        $data['types'] = array_combine($data['types'],$data['types']);

        $data['setting'] = $this->current_setting;

        $title = sprintf(lang('settings_edit_setting_title'), $this->current_setting->title);
        $this->template->set_title($title);
        $this->template->set_breadcrumb($title, 'settings/edit/' . $slug);
        $this->template->build('admin/edit', $data);
    }

    /**
     * Save the submitted edit form
     *
     * @return void
     */
    private function save()
    {
        // Set the validation rules
        $this->form_validation->set_rules('slug', 'lang:settings_slug_label', 'trim|required|alpha_dash|max_length[32]|min_length[5]|callback_unique_slug');
        $this->form_validation->set_rules('title', 'lang:settings_title_label', 'trim|required|max_length[32]|min_length[5]');
        $this->form_validation->set_rules('description', 'lang:settings_description_label', 'trim|max_length[255]');
        $this->form_validation->set_rules('type', 'lang:settings_type_label', 'trim|required|callback_type_check');
        $this->form_validation->set_rules('value', 'lang:settings_value_label', 'trim|max_length[255]');
        $this->form_validation->set_rules('options', 'lang:settings_options_label', 'trim|max_length[255]|callback_options_check');
        $this->form_validation->set_rules('module', 'lang:settings_module_label', 'trim|max_length[32]');

        if($this->form_validation->run($this))
        {
            // Everything is valid, lets save it back to the database
            $values = $this->extract_form_values();

            if($this->current_setting):
                $this->setting_model->update($this->current_setting->slug, $values);
            else:
                $this->setting_model->insert($values);
            endif;

            $this->status->set('success', lang('settings_changes_saved'));
            redirect('settings', REDIRECT_METHOD);
        }
        else
        {
            $this->status->set('error',$this->form_validation->_error_array);
        }
    }

    /**
     * Delete a setting
     *
     * @param string $slug Slug setting
     * @return void
     */
    public function delete($slug)
    {
        if($this->input->is_ajax_request())
        {
            // Get the setting
            if(($setting = $this->setting_model->get($slug)) == false)
            {
                $this->output->set_status_header('500');
                $this->output->set_output(lang('settings_setting_not_found'));
                return;
            }

            // If the setting is locked, don't allow it to be deleted
            if($setting->is_locked)
            {
                $this->output->set_status_header('500');
                $this->output->set_output(lang('settings_cannot_delete_locked_setting'));
                return;
            }

            try
            {
               $this->setting_model->delete($slug);
            }
            catch (BackendProException $ex)
            {
                $this->output->set_status_header('500');
                $this->output->set_output(lang('settings_an_error_occurred'));
            }
        }
        else
        {
            show_404(lang('settings_illegal_access_to_delete_page'));
        }
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

        // It doesn't make sense to have a checkbox which is required
        if($data['type'] == 'checkbox')
        {
            $data['is_required'] = 0;
        }

        // If we are editing a setting and it is locked, make sure we can't change the slug
        if($this->current_setting && $this->current_setting->is_locked)
        {
            unset($data['slug']);
        }

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
                $this->form_validation->set_message('options_check', lang('settings_validation_options_check_required'));
                return false;
            }

            // Check at least 1 option exists
            if(!preg_match("/^[^,]+(,[^,]+)*$/", $value))
            {
                $this->form_validation->set_message('options_check', lang('settings_validation_options_check_invalid'));
                return false;
            }
        }

        return true;
    }

    /**
     * Check the setting type given is one of those allowed
     *
     * @param string $value Value to check
     * @return bool
     */
    function type_check($value)
    {
        $types = $this->config->item('control_types','settings');

        if(!in_array($value, $types))
        {
            $this->form_validation->set_message('type_check', lang('settings_validation_type_check'));
            return false;
        }

        return true;
    }

    /**
     * Check the setting slug is unique
     *
     * @param string $value Value to check
     * @return bool
     */
    function unique_slug($value)
    {
        if($this->current_setting == false || $this->current_setting->slug != $value)
        {
            // There is no slug so we are adding a new setting, check for uniqueness
            if($this->setting_model->get($value) !== false)
            {
                // Existing setting found with matching slug
                $this->form_validation->set_message('unique_slug', lang('settings_validation_unique_slug'));
                return false;
            }
        }

        return true;
    }
}
 
/* End of manage.php */
/* Location: ./application/backendpro_modules/settings/controllers/manage.php */