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

            // Object keyword
            // I wouldn't advise changing this, it could corrupt current
            // preferences, the reason for needed this is to stop unserialze
            // errors spamming the log files
            $this->object_keyword = "BeP::Object::";

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

				if($this->object_keyword == substr($string,0,strlen($this->object_keyword)-1))
				{
					// Return object
					$object = substr($string,strlen($this->object_keyword));
					$this->_CACHE[$name] = unserialize($object);
					return $this->_CACHE[$name];
				}
				else
				{
					// Return string
					$this->_CACHE[$name] = $string;
					return $this->_CACHE[$name];
				}
			}

			log_message("error","The preference '".$name."' is not valid");
			return FALSE;
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
				$value = $this->object_keyword . serialize($value);

			return $this->update('Option',array('value'=>$value),array('name'=>$name));
		}
	}
?>