<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');
	/**
	 * Userlib Config Array
	 *
	 * Contains all configuration settings using by the Userlib authentication class
	 *
	 * @package			BackendPro
	 * @subpackage		Configurations
	 * @author				Adam Price
	 * @copyright			Copyright (c) 2008
	 * @license				http://www.gnu.org/licenses/lgpl.html
	 */

	/*
	|--------------------------------------------------------------------------
	| Authentication Actions
	|--------------------------------------------------------------------------
	| These are all the actions performed when an auth process has been completed
    | DO NOT SEND THE LOGIN ACTIONS BACK TO THE LOGIN CONTROLLER, IT WILL
    | CAUSE AN INFINITE LOOP
	*/
	$config['userlib_action_login'] = '';
	$config['userlib_action_logout'] = '';
	$config['userlib_action_register'] ='';
	$config['userlib_action_activation'] ='';
    $config['userlib_action_forgotten_password'] = 'auth/login';
	$config['userlib_action_admin_login'] = 'admin';
	$config['userlib_action_admin_logout'] = '';
    
    /*
    |--------------------------------------------------------------------------
    | User Profile Fields
    |--------------------------------------------------------------------------
    | Define here all custom user profile fields and their respective rules
    |
    | To define a new custom profile field, you must specify an associative array
    | from the database column name => Full Name/Rule. If no rule is given for 
    | a specific field it will not be validated.
    */
    $config['userlib_profile_fields'] = array();
    $config['userlib_profile_rules'] = array();
?>