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
 * Allows the user to update system settings
 *
 * @subpackage      Settings Module
 */
class Settings extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Check the user has access
        $this->user->has_access('Settings');

        $this->load->library('form_validation');
        $this->load->library('setting');
        $this->load->library('setting_controls');
        $this->load->model('setting_model');
        $this->load->helper('form');
        $this->lang->load('settings');        

        $this->template->set_title(lang('settings'));
        $this->template->set_breadcrumb(lang('settings'), 'settings');
    }

    /**
     * Display a tabbed view showing all settings in their groups
     *
     * @return void
     */
    public function index()
    {
        $settings = $this->get_settings();
        $data = array();
        
        foreach($settings as $setting)
        {
            // Create the form input control
            $setting->control = $this->setting_controls->render($setting);

            // Assign a module if none is given
            if($setting->module == '')
            {
                $setting->module = 'general';
            }

            $data['sections'][$setting->module] = ucfirst($setting->module);
            $data['settings'][$setting->module][] = $setting;
        }

        // Make sure if a general section exists, its the first section
        if(array_key_exists('general', $data['sections']))
        {
            $section = $data['sections']['general'];
            unset($data['sections']['general']);
            $data['sections'] = array('general' => $section) + $data['sections'];
        }

        $this->template->build('admin/index', $data);
    }

    /**
     * Perform validation on the setting from index(), save back to
     * the database if valid
     * 
     * @return void
     */
    public function save()
    {
        $settings = $this->get_settings();

        // Set the validation rules
        foreach($settings as $setting)
        {
            $rules = ($setting->is_required ? 'required' : '');
            $rules .= ($setting->validation_rules != '') ? '|' . $setting->validation_rules : '';

            $this->form_validation->set_rules($setting->slug, $setting->title, $rules);
        }

        if($this->form_validation->run())
        {
            // Everything was fine, save them
            foreach($settings as $setting)
            {
                // Only change if the value has changed
                $new_value = $this->setting_controls->get_value($setting);
				if($new_value != $setting->value)
				{
					$this->setting->set_item($setting->slug, $new_value);
				}
            }

            // Everything saved
            $this->status->set('success', lang('settings_saved'));
        }
        else
        {
            // Form error
            $this->status->set('error', $this->form_validation->_error_array);
        }

        // Show the setting page again
        $this->index();
    }

    /**
     * Get all settings based on the users access permissions
     * 
     * @return void
     */
    private function get_settings()
    {
        if($this->user->has_access('Settings', 'Manage', FALSE))
        {
            // If they can manage the settings, show all
            return $this->setting_model->get_all();
        }
        else
        {
            // If they can't manage them, only show GUI settings
            return $this->setting_model->get_all_gui();
        }
    }
}
 
/* End of settings.php */
/* Location: ./application/backendpro_modules/settings/controllers/settings.php */