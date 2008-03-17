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
	| Custom User Profile Fields
	|--------------------------------------------------------------------------
	| Here you can set each custom field column_name, label text
	|
	| For example array('first_name'=>"First Name"), would link a column
	| name first_name to the string "First Name"
	*/
	$config['userlib_profile_fields'] = array(
	);

	/*
	|--------------------------------------------------------------------------
	| Custom User Profile Rules
	|--------------------------------------------------------------------------
	| Here you can set each validation rule for a field name (IE the column name)
	*/
	$config['userlib_profile_rules'] = array(
	);

	/*
	|--------------------------------------------------------------------------
	| Admin & User Roles
	|--------------------------------------------------------------------------
	| What roles can be given to a user. The BackendPro roles
	| system works by inheritance. For example, Superadmin (id=1) can do
	| everything Admin (id=2) can do and a bit more. So id=1 is the highest
	| role level, while id=100 is the lowest. DO NOT CHANGE
	| SUPERADMIN/ADMIN/USER VALUES OF THIS ARRAY
	*/
	 $config['userlib_roles'] = array(
		// DO NOT CHANGE THE FOLLOWING
		'superadmin' 	=> 1,
		'admin'			=> 2,
		// Feel free to add extra roles here
		// 'Deputy' 			=> 30,
		// 'Power User' 	=> 40,
		// DO NOT CHANGE THE FOLLOWING
		'user'				=> 100);


?>