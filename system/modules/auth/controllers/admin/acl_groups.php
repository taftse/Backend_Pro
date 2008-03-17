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
     * @tutorial        BackendPro.pkg
     */

     // ---------------------------------------------------------------------------

    /**
     * ACL Groups
     * 
     * Provide the ability to manage ACL groups
     *
     * @package         BackendPro
     * @subpackage      Controllers
     */     
     class Acl_groups extends Admin_Controller
     {
         function Acl_groups()
         {
             // Call parent constructor
             parent::Admin_Controller();
             
             // Load files
             $this->lang->load('access_control');
             $this->load->model('access_control_model'); 
             
             // Set breadcrumb
             $this->page->set_crumb($this->lang->line('backendpro_access_control'),'auth/admin/access_control');
             $this->page->set_crumb($this->lang->line('access_groups'),'auth/admin/acl_groups'); 
             
             log_message('debug','ACL Groups Cass Initialized'); 
         }
         
         function index()
         {                                       
             // Display Page
             $data['header'] = $this->lang->line('access_groups');
             $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/groups";
             $data['module'] = 'auth';
             $this->load->view(Site_Controller::$_container,$data);
         }
         
         /**
          * Create Groups
          * 
          * @access public
          * @return void 
          */
         function create()
         {
             
             // Setup validation
             $this->load->library('validation');
             
             $fields['name'] = $this->lang->line('access_name');
             $fields['parent'] = $this->lang->line('access_parent_name');
             $rules['name'] = 'trim|required|min_length[3]';
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
                 $parent = $this->input->post('parent');  
                 $this->load->module_library('auth','khacl');  
                 
                 if($parent === FALSE){$parent=NULL;}
                 
                 if($this->khacl->aro->create($name,$parent))
                    flashMsg('success',sprintf($this->lang->line('backendpro_created'),'Group'));
                 else
                    flashMsg('warning',sprintf($this->lang->line('access_group_exists'),$name));
             }
             redirect('auth/admin/acl_groups','location');
         }   
         
         /**
          * Delete Groups
          * 
          * @access public
          * @return void 
          */
         function delete()
         {
             if(FALSE === ($groups = $this->input->post('select')))
                redirect('auth/admin/acl_groups','location'); 
                
             $this->load->module_library('auth','khacl');
             foreach($groups as $group)
             {
                 $this->khacl->aro->delete($group);
                 flashMsg('success',sprintf($this->lang->line('backendpro_deleted'),"Group '".$group."'"));
             }
             redirect('auth/admin/acl_groups','location');
         }     
     }
?>