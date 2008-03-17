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
     * ACL Permissions
     * 
     * Provide the ability to manage ACL permissions
     *
     * @package         BackendPro
     * @subpackage      Controllers
     */     
     class Acl_permissions extends Admin_Controller
     {
         function Acl_permissions()
         {
             // Call parent constructor
             parent::Admin_Controller();
             
             // Load files
             $this->lang->load('access_control');
             $this->load->model('access_control_model'); 
             
             // Set breadcrumb
             $this->page->set_crumb($this->lang->line('backendpro_access_control'),'auth/admin/access_control');
             $this->page->set_crumb($this->lang->line('access_permissions'),'auth/admin/acl_permissions'); 
             
             log_message('debug','ACL Permissions Cass Initialized'); 
         }
         
         function index()
         {                                       
             // Display Page
             $data['header'] = $this->lang->line('access_permissions');
             $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/permissions";
             $data['module'] = 'auth';
             $this->load->view(Site_Controller::$_container,$data);
         } 
         
         /**
          * Manage Permission
          * 
          * @access public
          * @param integer $id Permission ID
          * @return void 
          */
         function manage($id=NULL)
         {
             // Load required JS
             $this->page->set_asset('admin','js','access_control.js');
             
             // Set action defauts since this is needed for both CREATE & MODIFY
             $query = $this->access_control_model->fetch('axos');
             foreach($query->result() as $action)
                $this->validation->set_default_value('allow_'.$action->name,'N'); 
             
             if($id == NULL){
                // CREATE PERMISSION
                $data['header'] = $this->lang->line('access_create_permission');
                
                // Set form defaults
                $this->validation->set_default_value('allow','N');
                $this->validation->set_default_value('id','');  
             }
             else {
                // MODIFY PERMISSION
                $data['header'] = $this->lang->line('access_edit_permission');
                                                         
                // Fetch form data
                $this->validation->set_default_value('id',$id);
                $result = $this->access_control_model->getPermissions(NULL,array('acl.id'=>$id));
                $row = $result[$id];               
                $this->validation->set_default_value('aro',$row['aro']); 
                $this->validation->set_default_value('aco',$row['aco']);
                $this->validation->set_default_value('allow',($row['allow']?'Y':'N')); 
                
                if(isset($row['actions'])){
                    foreach($row['actions'] as $action)
                    {
                        $this->validation->set_default_value('action_'.$action['axo'],$action['axo']);
                        $this->validation->set_default_value('allow_'.$action['axo'],($action['allow']?'Y':'N'));
                    }
                }
             }
             
             // Display Page
             $this->page->set_crumb($data['header'],'auth/admin/acl_permissions/manage/'.$id); 
             $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/manage_permission";
             $data['module'] = 'auth';
             $this->load->view(Site_Controller::$_container,$data);
         }   
         
         /**
          * Save Permission
          * 
          * @access public
          * @return void 
          */
         function save()
         {
             $aro = $this->input->post('aro'); 
             $aco = $this->input->post('aco'); 
             $allow = $this->input->post('allow'); 
             $id = $this->input->post('id');
             
             $this->load->module_library('auth','khacl');
             
             // Remove old actions
             if($id)
                 $this->access_control_model->delete('access_actions',array('access_id'=>$id));
                 
             // Create permission                     
             // First we will process the actions
             foreach($_POST as $key=>$value)
             {
                 if(substr($key,0,7) == 'action_')
                 {
                    switch($this->input->post('allow_'.$this->input->post($key)))
                    {
                        case 'Y':$this->khacl->allow($aro,$aco,$this->input->post($key));break;
                        case 'N':$this->khacl->deny($aro,$aco,$this->input->post($key));break;
                    }
                 }
             }
             
             // Now process the main permission
             switch($allow)
             {  
                 case 'Y':$this->khacl->allow($aro,$aco);break;
                 case 'N':$this->khacl->deny($aro,$aco);break;
             }
             
             if($id)
                flashMsg('success',sprintf($this->lang->line('backendpro_modified'),'Permission'));
             else
                flashMsg('success',sprintf($this->lang->line('backendpro_created'),'Permission'));
                
             redirect('auth/admin/acl_permissions','location');             
         }
         
         /**
          * Delete Permissions
          * 
          * @access public
          * @return void 
          */
         function delete()
         {
             if(FALSE === ($permissions = $this->input->post('select')))
                redirect('auth/admin/acl_permissions','location'); 
                
             foreach($permissions as $permission)
             {
                 $this->access_control_model->delete('access',array('id'=>$permission)); 
                 $this->access_control_model->delete('access_actions',array('access_id'=>$permission)); 
             }
             flashMsg('success',sprintf($this->lang->line('backendpro_deleted'),"Permissions"));   
             redirect('auth/admin/acl_permissions','location');
         }
     }
?>