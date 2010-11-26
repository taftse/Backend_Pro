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
 * The settings library provides functions to retrive setting values from
 * the database.
 *
 * @subpackage      Settings Module
 */
class Setting
{
    /**
     * The current setting values stored in the database. By storing them
     * here it means we only have to do a single query.
     *
     * @var array
     */
    private $items = array();

    public function __construct()
    {
        $CI = &get_instance();

        $CI->load->model('settings/setting_model');
        $CI->load->helper('settings/setting');

        $this->populate_cache();

        log_message('debug', 'Settings Library loaded');
    }

    /**
     * Populate the cache with its initial values
     *
     * @return void
     */
    private function populate_cache()
    {
        log_message('debug', 'Populating the setting cache from the database');
        $CI = &get_instance();

        $settings = $CI->setting_model->get_all();
        
        foreach($settings as $setting)
        {
            $this->items[$setting->slug] = $setting->value;
        }
    }

    /**
     * Fetch a setting item value
     *
     * @param string $slug Setting slug to fetch
     * @param bool $use_cache Whether to fetch the value from cache or from the DB.
     * @return object
     */
    public function item($slug, $use_cache = TRUE)
    {
        // If we can use the cache and its there fetch it
        if($use_cache && in_array($slug, array_keys($this->items)))
        {
            return $this->items[$slug];
        }

        $CI = &get_instance();

        // Fetch the value from the database
        $setting = $CI->setting_model->get($slug);

        // And return its value
        return $this->item[$setting->slug] = $setting->value;
    }

    /**
     * Set a new value for a setting
     * 
     * @param string $slug Setting slug
     * @param string $value New value
     * @return void
     */
    public function set_item($slug, $value)
    {
        $CI = &get_instance();

        $CI->setting_model->set($slug, $value);
        $this->items[$slug] = $value;
    }
}

/* End of file Setting.php */
/* Location: ./application/backendpro_modules/settings/libraries/Setting.php */