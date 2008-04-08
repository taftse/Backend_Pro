<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
    /**
     * BackendPro
     *
     * A website backend system for developers for PHP 4.3.2 or newer
     *
     * @package         BackendPro
     * @author          Adam Price
     * @copyright       Copyright (c) 2008
     * @license         http://www.gnu.org/licenses/lgpl.html
     */

     // ---------------------------------------------------------------------------

    /**
     * Access Control
     * 
     * Display a splash page showing the access control options
     *
     * @package         BackendPro
     * @subpackage      Controllers
     */     
     class Access_control extends Admin_Controller
     {
         function Access_control()
         {
             // Call parent constructor
             parent::Admin_Controller();
             
             // Load files
             $this->lang->load('access_control');
             
             // Set breadcrumb
             $this->page->set_crumb($this->lang->line('backendpro_access_control'),'auth/admin/access_control');
             
             // Check for access permission
             check('Access Control');
             
             log_message('debug','Access Control Class Initialized'); 
         }
         
         function index()
         {
             // Display Page
             $data['header'] = $this->lang->line('backendpro_access_control');
             $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/home";
             $data['module'] = 'auth';
             $this->load->view(Site_Controller::$_container,$data);
             return;
         }
     }
?>