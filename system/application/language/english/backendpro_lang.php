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
	 * @author			Adam Price
	 * @copyright		Copyright (c) 2008
	 * @license			http://www.gnu.org/licenses/lgpl.html
	 */
     
     /* ------------------------------------------------------- GENERAL ----- 
      * Define any general words here, e.g. Delete, Save, Edit 
      */                  
     $lang['general_id'] = "ID";                                         
     $lang['general_top'] = 'Top';
     $lang['general_add'] = "Add";
     $lang['general_edit'] = "Modify";
     $lang['general_delete'] = "Delete";
     $lang['general_save'] = "Save";
     $lang['general_documentation'] = "Documentation";
     
     $lang['backendpro_control_panel'] = "Control Panel";
     $lang['backendpro_view_website'] = 'View Website'; 
     
     /* Maintenance Strings */
     $lang['backendpro_under_maintenance'] = "Under Maintenance";
     $lang['backendpro_maintenance_login'] = "If you know you have permission to access the website please login as usual " . anchor('auth/login','here') . ".";
     $lang['backendpro_site_off'] = "The website is currently in maintenance mode, only people with access to this control panel, can access it.";
     
     /* Status Messages */
     $lang['backendpro_remove_install'] = "Please remove the install directory from your base path, it is not safe to leave it in the system";
     $lang['backendpro_action_failed'] = "The action '%s' has failed, please try again";    
     
     /* All Main Controller Names and menu items */
     $lang['backendpro_dashboard'] = "Dashboard";
     $lang['backendpro_system'] = 'System'; 
     $lang['backendpro_members'] = 'Members'; 
     $lang['backendpro_access_control'] = 'Access Control';
     $lang['backendpro_settings'] = 'Settings'; 
     $lang['backendpro_utilities'] = 'Utilities'; 
     
?>