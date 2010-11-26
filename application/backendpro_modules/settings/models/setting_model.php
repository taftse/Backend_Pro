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
 * The Settings Model provides database functions to get/set setting
 * values.
 *
 * @subpackage      Settings Module
 */
class Setting_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();

        $tables = $this->config->item('tables','backendpro');

        // Set base model properties
        $this->table = $tables['settings'];
        $this->primary_key = 'slug';

        log_message('debug', 'Setting_model class loaded');
    }

    /**
     * Returns an empty instance of a Setting object
     * 
     * @return StdClass
     */
    public function get_object()
    {
        $object = new StdClass();

        $object->slug = NULL;
        $object->title = NULL;
        $object->description = NULL;
        $object->type = 'text';
        $object->value = NULL;
        $object->options = NULL;
        $object->validation_rules = NULL;
        $object->is_required = 0;
        $object->is_gui = 1;
        $object->module = NULL;

        return $object;
    }

    /**
     * Get all settings which can be shown on the GUI
     *
     * @return void
     */
    public function get_all_gui()
    {
        return parent::get_all_by(array('is_gui' => 1));
    }

    /**
     * Set a new value for setting
     *
     * @param string $slug Setting slug to update
     * @param string $value Value to save
     * @return void
     */
    public function set($slug, $value)
    {
        parent::update($slug, array('value' => $value));
    }

    /**
     * Get all allowed setting types
     *
     * @return array 
     */
    public function get_types()
    {
        $field = $this->db->query('SHOW COLUMNS FROM ' . $this->table . ' LIKE \'type\'')->row();

        $enum_array = array();
        preg_match_all( "/'(.*?)'/" , $field->Type, $enum_array);

        return $enum_array[1];
    }
}

/* End of file setting_model.php */
/* Location: ./application/backendpro_modules/settings/models/setting_model.php */