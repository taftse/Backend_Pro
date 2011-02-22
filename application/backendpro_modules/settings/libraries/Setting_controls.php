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
 * Handle rendering and saving values from setting controls.
 *
 * TODO: This would be perfect to be a driver, a single driver for each control type
 *
 * @subpackage      Settings Module
 */
class Setting_controls
{
    public function __construct()
    {
        $CI =& get_instance();

        $CI->load->helper('form');

        log_message('debug', 'Setting_controls Library Loaded');
    }

    /**
     * Render control
     *
     * @param object $setting Setting to render
     * @return string
     */
    public function render($setting)
    {
        switch($setting->type)
        {
            case 'text':
                return $this->render_text($setting);
                break;

            case 'textarea':
                return $this->render_textarea($setting);
                break;

            case 'password':
                return $this->render_password($setting);
                break;

            case 'select':
                return $this->render_select($setting);
                break;

            case 'select-multiple':
                return $this->render_select($setting, TRUE);
                break;

            case 'checkbox':
                return $this->render_checkbox($setting);
                break;

            default:
                show_error(sprintf(lang('settings_unknown_control_type'), $setting->type));
                break;
        }
    }

    /**
     * Get submitted value from control
     *
     * @param object $setting Setting to render
     * @return string
     */
    public function get_value($setting)
    {
        $CI =& get_instance();
        
        switch($setting->type)
        {
            case 'text':
            case 'textarea':
            case 'password':
            case 'select':
            case 'checkbox':
                return trim($CI->input->post($setting->slug));
                break;

            case 'select-multiple':
                return $this->get_multiselect_value($setting);
                break;

            default:
                show_error(sprintf(lang('settings_unknown_control_type'), $setting->type));
                break;
        }
    }

    /**
     * Get a multi-select value and convert into a flat string
     *
     * @param object $setting Setting to retrieve value from
     * @return string
     */
    private function get_multiselect_value($setting)
    {
        $CI =& get_instance();

        $values = $CI->input->post($setting->slug);

        if($values === FALSE)
        {
            return '';
        }
        else
        {
            return implode(',', $values);
        }
    }

    /**
     * Render a text control
     *
     * @param object $setting Setting to render
     * @return string
     */
    private function render_text($setting)
    {
        return form_input($setting->slug, set_value($setting->slug, $setting->value));
    }

    /**
     * Render a textarea control
     *
     * @param object $setting Setting to render
     * @return string
     */
    private function render_textarea($setting)
    {
        return form_textarea($setting->slug, set_value($setting->slug, $setting->value));
    }

    /**
     * Render a password control
     *
     * @param object $setting Setting to render
     * @return string
     */
    private function render_password($setting)
    {        
        return form_password($setting->slug, set_value($setting->slug, $setting->value));
    }

    /**
     * Render a select dropdown control
     *
     * @param object $setting Setting to render
     * @param bool $multiselect Whether to make the control a multi-select
     * @return string
     */
    private function render_select($setting, $multiselect = FALSE)
    {
        $options = explode(',', $setting->options);
        $options = array_combine($options, $options);

        if($multiselect)
        {
            $CI =& get_instance();

            if($CI->input->post($setting->slug) !== FALSE)
            {
                // Something was submitted, make this the selected item
                $selected = $CI->input->post($setting->slug);
            }
            else
            {
                // Use the current values
                $selected = explode(',', $setting->value);
            }

            return form_multiselect($setting->slug . '[]', $options, $selected);
        }
        else
        {
            return form_dropdown($setting->slug, $options, set_value($setting->slug, $setting->value));
        }
    }

    /**
     * Render a checkbox control
     *
     * @param object $setting Setting to render
     * @return string
     */
    private function render_checkbox($setting)
    {
        return form_checkbox($setting->slug, '1');
    }
}
 
/* End of Setting_controls.php */
/* Location: ./application/backendpro_modules/settings/libraries/Setting_controls.php */