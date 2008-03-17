<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');
	/**
	 * Status Helper file
	 *
	 * Contains shortcuts to well used Status library commands
	 *
	 * @package			BackendPro
	 * @subpackage		Helpers
	 * @author				Adam Price
	 * @copyright			Copyright (c) 2007
	 * @license				http://www.gnu.org/licenses/lgpl.html
	 */

	/**
	 * Set Flash Message
	 *
	 * Set a new flash message for the system
	 *
	 * @access public
	 * @param string $type Message type, either info,sucess,error,warning
	 * @param string $message Message
	 * @return boolean
	 */
	function flashMsg($type=NULL, $message=NULL)
	{
		$obj = &get_instance();
		return $obj->status->set($type, $message);
	}

	/**
	 * Display status messages
	 *
	 * If no type has been given it will display every message,
	 * otherwise it will only show and remove that certain type of
	 * message
	 *
	 * @access public
	 * @param string $type Error type to display
	 * @return string
	 */
	 function displayStatus($type=NULL)
	 {
		$obj = &get_instance();
		return $obj->status->display($type);
	 }
?>