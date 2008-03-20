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
     * ACL Actions
     * 
     * Provide the ability to manage ACL actions
     *
     * @package         BackendPro
     * @subpackage      Controllers
     */     
     class Acl_actions extends Admin_Controller
     {
         function Acl_actions()
         {
             // Call parent constructor
             parent::Admin_Controller();
             
             // Load files
             $this->lang->load('access_control');
             $this->load->model('access_control_model'); 
             
             // Set breadcrumb
             $this->page->set_crumb($this->lang->line('backendpro_access_control'),'auth/admin/access_control');
             $this->page->set_crumb($this->lang->line('access_actions'),'auth/admin/acl_actions'); 
             
             // Check for access permission
             check('Actions');
             
             log_message('debug','ACL Actions Class Initialized'); 
         }
         
         /**
          * View Actions
          * 
          * @access public
          * @return void 
          */
         function index()
         {                                     
             // Display Page
             $data['header'] = $this->lang->line('access_actions');
             $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/actions";
             $data['module'] = 'auth';
             $this->load->view(Site_Controller::$_container,$data);
         }
         
         /**
          * Create Action
          * 
          * @access public
          * @return void 
          */
         function create()
         {
             // Setup validation
             $this->load->library('validation');
             
             $fields['name'] = $this->lang->line('access_name');
             $rules['name'] = 'trim|required|min_length[3]|max_length[254]';
             $this->validation->set_fields($fields);
             $this->validation->set_rules($rules);
             
             if($this->validation->run() === FALSE)
             {
                 // FAIL
                 $this->validation->output_errors();                 
             }
             else
             {
                 // PASS
                 $name = $this->input->post('name');
                 $this->load->module_library('auth','khacl');  
                 
                 if($this->khacl->axo->create($name))
                    flashMsg('success',sprintf($this->lang->line('access_action_created'),$name));
                 else
                    flashMsg('warning',sprintf($this->lang->line('access_action_exists'),$name));
             }
             redirect('auth/admin/acl_actions','location');
         }   
         
         /**
          * Delete Actions
          * 
          * @access public
          * @return void 
          */
         function delete()
         {
             if(FALSE === ($actions = $this->input->post('select')))
                redirect('auth/admin/acl_actions','location'); 
                
             $this->load->module_library('auth','khacl');
             foreach($actions as $action)
             {
                 $this->khacl->axo->delete($action);
                 flashMsg('success',sprintf($this->lang->line('access_action_deleted'),$action));
             }
             redirect('auth/admin/acl_actions','location');
         }     
     }
?>