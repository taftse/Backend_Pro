<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 4.3.2 or newer
 *
 * @package			BackendPro
 * @author				Adam Price
 * @copyright			Copyright (c) 2008
 * @license				http://www.gnu.org/licenses/lgpl.html
 * @tutorial				BackendPro.pkg
 */

 // ---------------------------------------------------------------------------

/**
 * Preference_model
 *
 * Model used to retrive webite options
 *
 * @package			BackendPro
 * @subpackage		Models
 */
	class Preference_model extends Base_model
	{
		/**
		 * Constructor
		 */
		function Preference_model()
		{
			// Call parent constructor
			parent::Base_model();

			$this->_TABLES = array('Option' => $this->config->item('backendpro_table_prefix') . 'preferences');

            // Cache to store already fetched items
            $this->_CACHE = array();
            
			log_message('debug','Preference_model Class Initialized');
		}

		/**
		 * Get Option
		 *
		 * Get a option with name $name from the database
		 * If the item is serialized, unserialize it and return object
		 *
		 * @access public
		 * @param string $name Option name
		 * @return mixed
		 */
		function item($name = NULL)
		{
			if($name == NULL)
			{
				return;
			}
            
            // Check in cache first
            if( isset($this->_CACHE[$name]))
                return $this->_CACHE[$name];

			$query = $this->fetch('Option','value',null,array('name'=>$name));

			if($query->num_rows() != 0)
			{
				$option = $query->row();
				$string = $option->value;

                log_message('debug',"Fetching the preference '".$name."'");
				if( FALSE === ($object = @unserialize($string)))
				{
					// String was not an object
                    $this->_CACHE[$name] = $string;
					return $string;
				}
				else
				{
					// Return object
                    $this->_CACHE[$name] = $object;
					return $object;
				}
			}
			else
			{
				show_error("The option '".$name."' is not valid.");
				return FALSE;
			}
		}

		/**
		 * Update Option Value
		 *
		 * Updates an option value in the database
		 *
		 * @access public
		 * @param string $name Option name
		 * @param mixed $value Option value
		 * @return boolean
		 */
		function set_item($name = NULL, $value = NULL)
		{
			if($name == NULL)
			{
				return FALSE;
			}

			if(is_array($value))
			{
				$value = serialize($value);
			}

			return $this->update('Option',array('value'=>$value),array('name'=>$name));
		}
	}
?>