<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * BackendPro
     *
     * A website backend system for developers for PHP 4.3.2 or newer
     *
     * @package		    BackendPro
     * @author			Adam Price
     * @copyright		Copyright (c) 2008
     * @license			http://www.gnu.org/licenses/lgpl.html
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
			if( is_null($name))
                return;
            
            // See if we have already got the setting
            if( isset($this->_CACHE[$name]))
                return $this->_CACHE[$name];

            // Fetch setting from database
			$query = $this->fetch('Option','value',null,array('name'=>$name));

			if($query->num_rows() != 0)
			{
				$row = $query->row();
				$string = $row->value;

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
		 * Set Option
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
			if( is_null($name))
				return FALSE;

			if( is_array($value))
				$value = serialize($value);

			return $this->update('Option',array('value'=>$value),array('name'=>$name));
		}
	}
?>