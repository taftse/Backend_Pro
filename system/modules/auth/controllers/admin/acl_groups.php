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
             
             // Check for access permission
             check('Groups');
             
             log_message('debug','ACL Groups Cass Initialized'); 
         }
         
         /**
          * View Groups
          * 
          * @access public
          * @return void 
          */
         function index()
         {                                       
             // Display Page
             $data['header'] = $this->lang->line('access_groups');
             $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/groups";
             $data['module'] = 'auth';
             $this->load->view(Site_Controller::$_container,$data);
         }
         
         function form($id = NULL)
         {             
             // Setup form validation
             $this->load->library('validation');
             $fields['id'] = "ID";
             $fields['name'] = $this->lang->line('access_name'); 
             $fields['disabled'] = $this->lang->line('access_disabled'); 
             $fields['parent'] = $this->lang->line('access_parent_name');
             $this->validation->set_fields($fields);
             
             $rules['name'] = "trim|required|max_length[254]";
             $rules['parent'] = "required";
             
             if( ! is_null($id) AND ! $this->input->post('submit'))
             {
                 // Load values into form
                 /*$node = $this->access_control_model->group->getNodeFromId($id);
                 
                 // Check it isn't the root
                 if( $this->access_control_model->resource->checkNodeIsRoot($node)){
                     flashMsg('warning',sprintf($this->lang->line('access_resource_root'),$node['name']));
                     redirect('auth/admin/acl_resources');
                 }
                 
                 $parent = $this->access_control_model->resource->getAncestor($node);
                 $this->validation->set_default_value('id',$id);
                 $this->validation->set_default_value('name',$node['name']); 
                 $this->validation->set_default_value('parent',$parent['name']);*/ 
             }
             elseif( $this->input->post('submit'))
             {
                 // Form submited, check rules
                 $this->validation->set_rules($rules);
             }
             
             if($this->validation->run() === FALSE)
             {
                 // Display Errors
                 $this->validation->output_errors();
                 
                 // Get Resources
                 $data['groups'] = $this->access_control_model->buildACLDropdown('groups');
                 
                 // Display Page  
                 $data['header'] = (is_null($id)?$this->lang->line('access_create_group'):$this->lang->line('access_edit_group'));
                 $this->page->set_crumb($data['header'],'auth/admin/acl_groups/form/'.$id);   
                 $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/form_group";
                 $data['module'] = 'auth';
                 $this->load->view(Site_Controller::$_container,$data);
             }
             else
             {   
                 $name = $this->input->post('name');   
                 $parent = $this->input->post('parent'); 
                 
                 if( is_null($id))
                 {            
                     // Create Resource
                     $this->load->library('khacl');                     
                     
                     $this->db->trans_start();
                     if( ! $this->khacl->aco->create($name,$parent))
                     {
                         flashMsg('warning',sprintf($this->lang->line('access_resource_exists'),$name));
                         redirect('auth/admin/acl_resources/form'); 
                     }  
                     
                     $this->access_control_model->insert('resources',array('id'=>$this->db->insert_id()));
                     
                     if( $this->db->trans_status() === TRUE)
                     {
                         $this->db->trans_commit();
                         flashMsg('success',sprintf($this->lang->line('access_resource_created'),$name)); 
                     }
                     else
                     {
                         $this->db->trans_rollback(); 
                         flashMsg('error',sprintf($this->lang->line('backendpro_action_failed'),$this->lang->line('access_create_resource'))); 
                     }  
                 }
                 else
                 {
                     $id = $this->input->post('id');
                     // Update Resource
                     $node = $this->access_control_model->resource->getNodeFromId($id);
                     $new_parent = $this->access_control_model->resource->getNodeWhere("name='".$parent."'");
                     
                     // Check the assigment isn't illeagal
                     if($this->access_control_model->resource->checkNodeIsChildOrEqual($new_parent,$node)){
                        flashMsg('warning',sprintf($this->lang->line('access_resource_illegal_assignment'),$name));
                        redirect('auth/admin/acl_resources/form/'.$id);   
                     }
                     
                     $this->access_control_model->resource->setNodeAsLastChild($node,$new_parent);
                     flashMsg('success',sprintf($this->lang->line('access_resource_saved'),$name)); 
                 }
                 redirect('auth/admin/acl_resources');
             }
         }
         
         /**
          * Manage a group
          * 
          * @access public
          * @param integer $id Group ID
          * @return void
          */
         function manage($id)
         {
             // Stop modifying root
             if($id==1)
                redirect('auth/admin/acl_groups');
             
             $fields['id'] = $this->lang->line('access_id');    
             $fields['name'] = $this->lang->line('access_name');    
             $fields['parent'] = $this->lang->line('access_parent');
             $fields['disabled'] = $this->lang->line('access_disabled');
             $this->validation->set_fields($fields);        
             
             // Get group details
             $query = $this->access_control_model->fetch('groups','disabled',NULL,array('id'=>$id));
             $group = $query->row_array();
             
             // Get node details
             $obj = & $this->access_control_model->group;
             $node = $obj->getNodeFromId($id);
             $parent = $obj->getAncestor($node);
             
             $group['id'] = $id;
             $group['name'] = $node['name'];
             $group['parent'] = $parent['name'];   
             
             $this->validation->set_default_value($group);            
             
             // Display Page
             $data['header'] = $this->lang->line('access_modify_group');
             $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/modify_group";
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
             $rules['parent'] = 'required';
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
                 $disabled = $this->input->post('disabled');   
                 $this->load->module_library('auth','khacl');  
                 
                 // Create Group
                 $this->db->trans_begin();                 
                 if( ! $this->khacl->aro->create($name,$parent)){
                    flashMsg('warning',sprintf($this->lang->line('access_group_exists'),$name));
                    $fail = TRUE;
                 } else {
                     // Add extra group info
                     $this->access_control_model->insert('groups',array('id'=>$this->db->insert_id(),'disabled'=>$disabled));
                 }                 
                 
                 if($this->db->trans_status() === FALSE OR isset($fail)){
                     $this->db->trans_rollback();
                 } else {
                     $this->db->trans_commit();
                     flashMsg('success',sprintf($this->lang->line('backendpro_created'),'Group')); 
                 }
             }
             redirect('auth/admin/acl_groups','location');
         }   
         
         /**
          * Modify a group 
          */
         function modify()
         {
             $id = $this->input->post('id');
             $disabled = $this->input->post('disabled');
             $name = $this->input->post('name');
             $parent = $this->input->post('parent');
             
             // Check they didn't assign the parent as itself
             if($name == $parent)
             {
                 flashMsg('warning',$this->lang->line('access_parent_loop_created')); 
                 redirect('auth/admin/acl_groups/manage/'.$id);
             }
             
             
             // Update the disabled value
             $this->access_control_model->update('groups',array('disabled'=>$disabled),array('id'=>$id));
             
             // Move the node
             $node = $this->access_control_model->group->getNodeWhere("name='".$name."'");
             $new_parent = $this->access_control_model->group->getNodeWhere("name='".$parent."'");
             $this->access_control_model->group->setNodeAsLastChild($node,$new_parent);
             
             flashMsg('success',sprintf($this->lang->line('backendpro_saved'),"Group '".$name."'")); 
             redirect('auth/admin/acl_groups');
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
                 // Check the group we are deleting isn't the default, if so disalow it
                 $query = $this->access_control_model->fetch('aros','id',NULL,array('name'=>$group));
                 $row = $query->row();
                 if($row->id == $this->preference->item('default_user_group')){
                    flashMsg('error',sprintf($this->lang->line('accces_delete_default'),$group));
                    continue;
                 }              
                 
                 if( $this->access_control_model->delete('groups',array('id'=>$row->id,'name'=>$group)))
                    flashMsg('success',sprintf($this->lang->line('backendpro_deleted'),$group));
                 else
                    flashMsg('warning',sprintf($this->lang->line('backendpro_deleted_fail'),$group));
             }
             redirect('auth/admin/acl_groups','location');
         }     
     }
?>