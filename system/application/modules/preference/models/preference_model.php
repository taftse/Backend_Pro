<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 4.3.2 or newer
 *
 * @package         BackendPro
 * @author          Adam Price <adam@kaydoo.co.uk?
 * @copyright       2008-2009, Adam Price
 * @license         http://www.gnu.org/licenses/lgpl.html LGPL
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

/**
 * Preference Model
 * 
 * @package         BackendPro
 * @subpackage      Models
 */
class Preference_model extends Model
{
    /**
	 * Preference Cache
	 * 
	 * @var array
	 */
	var $preference_cache = array();
	
	/**
	 * Object Keyword
	 * 
	 * This is the keyword which prepends a serialized object
	 * Using this the system knows when to unserialize a string
	 * or to use it raw.
	 * 
	 * Don't change this unless you have a very good reason. It
	 * is needed otherwise it will spam the logs with errors.
	 * 
	 * @var string
	 */
 	var $object_keyword = 'BeP::Object::';
 	
	function Preference_model()
	{
		parent::Model();		

		define(PREFERENCE_TABLE, config('backendpro_table_prefix') . 'preferences');

		log_message('debug','BackendPro : Preference_model class loaded');
	}

	/**
	 * Get Preference
	 *
	 * Get a preference from the database
	 * If the item is serialized, unserialize it and return object
	 *
	 * @param string $name Option name
	 * @return mixed
	 */
	function item($name)
	{
		// See if we have already got the setting
		if (isset($this->preference_cache[$name]))
		{
			return $this->preference_cache[$name];
		}

		// Get all preferences and fill the cache
		$this->db->select('name, value');
		$this->db->from(PREFERENCE_TABLE);
		$query = $this->db->get();

		foreach($query->result() as $row)
		{
			if ($this->object_keyword == substr($row->value,0,strlen($this->object_keyword)))
			{
				// Return object
				$object = substr($row->value,strlen($this->object_keyword));
				$this->preference_cache[$row->name] = unserialize($object);
			}
			else
			{
				// Return string
				$this->preference_cache[$row->name] = $row->value;
			}			
		}

		if (isset($this->preference_cache[$name]))
		{
			return $this->preference_cache[$name];
		}
		else
		{
			log_message('error','BackendPro->Preference_model->item : Invalid preference: ' . $name);
			return FALSE;
		}		
	}

	/**
	 * Set Option
	 *
	 * Updates an option value in the database
	 *
	 * @param string $name Option name
	 * @param mixed $value Option value
	 * @return boolean
	 */
	function set_item($name, $value)
	{
		if (is_null($name))
		{
			return FALSE;
		}

		$this->preference_cache[$name] = $value;

		if (is_array($value))
		{
			$value = $this->object_keyword . serialize($value);
		}
		
		log_message('debug','BackendPro->Preference_model->set_item : Preference value changed, ' . $name . ' = ' . $value);
		$this->db->where('name', $name);
        return $this->db->update(PREFERENCE_TABLE, array('value'=>$value)); 
	}
}

/* End of file preference_model.php */
/* Location: ./system/application/modules/preference/models/preference_model.php */