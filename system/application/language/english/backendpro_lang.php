<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	/**
	 * Main Language Array
	 *
	 * This file contains the top most language strings for BackendPro.
	 * This file is included by default with the BackendPro library.
	 *
	 * Any language string you need through the backend or front end
	 * should be placed in here. IE controller names for menus etc
	 *
	 * @package			BackendPro
	 * @subpackage		Languages
	 * @author				Adam Price
	 * @copyright			Copyright (c) 2008
	 * @license				http://www.gnu.org/licenses/lgpl.html
	 */
     
     $lang['backendpro_top'] = 'Top';
     $lang['backendpro_maintenance'] = 'Under Maintenance';
     $lang['backendpro_maintenance_login'] = "If you are an administrator you may still use the website by logging in " . anchor('auth/login','here') . ".";

     $lang['backendpro_control_panel'] = "Control Panel";
     $lang['backendpro_view_website'] = 'View Website';   
     
     /* Status Messages */
     $lang['backendpro_created'] = '%s created successfully';
     $lang['backendpro_saved'] = '%s saved successfully';
     $lang['backendpro_deleted'] = '%s deleted successfully';
     $lang['backendpro_in_maintenance_mode'] = "The website is currently in maintenance mode, only super administrators can view it.";
     
     $lang['backendpro_confirm_deletes'] = 'Are you SURE you want to delete these records?';
     
     /* All Main Controller Names and menu items */
     $lang['backendpro_dashboard'] = "Dashboard";
     $lang['backendpro_system'] = 'System'; 
     $lang['backendpro_members'] = 'Members'; 
     $lang['backendpro_access_control'] = 'Access Control';
     $lang['backendpro_settings'] = 'Settings'; 
     $lang['backendpro_utilities'] = 'Utilities'; 
     
?>