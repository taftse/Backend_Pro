<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');
	/**
	 * Userlib Helper File
	 *
	 * Contains shortcuts to well used Userlib functions
	 *
	 * @package			BackendPro
	 * @subpackage		Helpers
	 * @author				Adam Price
	 * @copyright			Copyright (c) 2007
	 * @license				http://www.gnu.org/licenses/lgpl.html
	 */
     
     function check($resource,$action=NULL,$redirect=TRUE)
     {
         $CI = & get_instance();
         return $CI->userlib->check($resource,$action,$redirect);
     }
     
     function is_user()
     {
         $CI = & get_instance();
         return $CI->userlib->is_user();
     }
?>