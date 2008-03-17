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
     * Access Control
     * 
     * Allow the user to manage the access permissions for the website.
     *
     * @package         BackendPro
     * @subpackage      Controllers
     */     
     class Access extends Admin_Controller
     {
         function Access()
         {
             // Call parent constructor
             parent::Admin_Controller();
             
             // Load files
             $this->lang->load('access_control');
             $this->load->model('access_control_model'); 
             
             // Set breadcrumb
             $this->page->set_crumb($this->lang->line('backendpro_access_control'),'auth/admin/access');
             
             // Load javscript needed
             $this->page->set_asset('admin','js','access_control.js');
             
             log_message('debug','Access Class Initialized'); 
         }
         
         /**
          * Index Page
          * 
          * Display spash screen for different access options
          * 
          * @access public
          * @return void 
          */
         function index()
         {             
            // Display Page
            $data['header'] = $this->lang->line('backendpro_access_control');
            $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/menu";
            $data['module'] = 'auth';
            $this->load->view(Site_Controller::$_container,$data);
            return;
         }
         
         /**
          * Permissions Page
          * 
          * Display all permissions
          * 
          * @access public
          * @param numeric $offset Record fetch offset
          * @return void 
          */
         function permissions($offset=0)
         {
             flashMsg('info',$this->lang->line('access_permissions_table_desc'));
             // Load files
             $this->load->library('pagination');   
             
             // If items marked for delete, delete them
             if($this->input->post('delete'))
             {
                 foreach($this->input->post('selected') as $item)
                 {
                     $this->access_control_model->deletePermission($item);
                 }
                 
                 flashMsg('success',sprintf($this->lang->line('backendpro_deleted'),$this->lang->line('access_permissions')));
             }
             
             // Fetch all permissions
             $display_per_page = 20; 
             $total_rows = count($this->access_control_model->getPermissions());             
             $data['permissions'] = $this->access_control_model->getPermissions(array('limit'=>$display_per_page,'offset'=>$offset));
             
             // Setup pageination
             $config['base_url'] = site_url('auth/admin/access/permissions');
             $config['total_rows'] = $total_rows;
             $config['per_page'] = $display_per_page;
             $config['uri_segment'] = 5;
             $this->pagination->initialize($config);
             $data['pageination'] = $this->pagination->create_links();
             
             // Display Page
             $this->page->set_crumb($this->lang->line('access_permissions'),'auth/admin/access/permissions'); 
             $data['header'] = $this->lang->line('access_permissions');
             $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/permissions";
             $data['module'] = 'auth';
             $this->load->view(Site_Controller::$_container,$data);
             return;
         }
         
         /**
          * Manage Permissions
          * 
          * Show a form to either add/edit a permission
          * 
          * @access public
          * @param mixed $id ACL permission ID
          * @return void 
          */
         function manage_permission($id=NULL)
         {
             $this->load->library('validation');
             if($id == NULL) 
                 $data['header'] = $this->lang->line('access_create_permission'); // Add permission  
             else 
                 $data['header'] = $this->lang->line('access_edit_permission'); // Edit permission               
             
             // Load Validation library at setup fields+ruels
             $fields['aro'] = $this->lang->line('access_groups');
             $fields['aco'] = $this->lang->line('access_resources');
             $fields['id'] = "ID";
             $rules['aro'] = 'required|alpha_dash';
             $rules['aco'] = 'required|alpha_dash';
             $this->validation->set_fields($fields);
             $this->validation->set_rules($rules);
             
             // Fetch values from DB  for edit form
             if(!$this->input->post('submit') && $id != NULL)
             {
                 foreach($this->access_control_model->getPermission($id) as $key=>$value)
                 {
                    $this->validation->{$key} = $value;
                 }
             }
             
             // Set custom message for resource & group trees
             $this->validation->set_message('required',$this->lang->line('access_required'));             
             
             if($this->validation->run() === FALSE)
             {
                $this->validation->output_errors();
                
                // Build trees
                $data['resources'] = $this->access_control_model->buildResourceTree('permissionResourcesTree');  
                $data['groups'] = $this->access_control_model->buildGroupTree('permissionGroupsTree'); 
                
                // Build Actions List
                $data['actions'] = '';
                $query = $this->access_control_model->getActions();
                foreach($query->result() as $action)
                {
                    $name = ucwords(str_replace('_',' ',$action->name));
                    $checkbox = 'action_'.$action->name;
                    $radio = 'allow_'.$action->name;   
                    $data['actions'] .= form_checkbox($checkbox,$action->name,$this->validation->set_checkbox($checkbox,$action->name));
                    $data['actions'] .= $name . "<br>\n";
                    
                    $data['actions'] .= '<div id="'.$radio.'" class="action_item">';
                    $data['actions'] .= form_radio($radio,'Y',$this->validation->set_radio($radio,'Y')) . $this->lang->line('access_allow');
                    $data['actions'] .= form_radio($radio,'N',$this->validation->set_radio($radio,'N')) . $this->lang->line('access_deny') . '</div>';
                }
                
                // Display form
                $this->page->set_crumb($this->lang->line('access_permissions'),'auth/admin/access/permissions');  
                $this->page->set_crumb($data['header'],'auth/admin/access/manage_permission/'.$id); 
                $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/manage_permission";
                $data['module'] = 'auth';
                $this->load->view(Site_Controller::$_container,$data);
                return;
             }
             else
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
                 
                 // Redirect back
                 if($id)
                    flashMsg("success",sprintf($this->lang->line('backendpro_modified'),"Permission"));    
                 else
                    flashMsg("success",sprintf($this->lang->line('backendpro_created'),"Permission"));
                 redirect('auth/admin/access/permissions','location');
             }
         }
         
         /**
          * Advanced Permissions
          * 
          * View the advanced verson of the system access lists.
          * Instead of just showing the permissions, it displays
          * what groups have access to what.
          * 
          * @access public
          * @return void 
          */
         function advanced_permissions()
         {
             return;
         }
         
         /**
          * Display groups
          * 
          * @access public
          * @return void 
          */
         function groups()
         {
             flashMsg('info',$this->lang->line('access_table_desc'));
             
             // If items marked for delete, delete them
             if($this->input->post('delete'))
             {
                 $this->load->module_library('auth','khacl'); 
                 foreach($this->input->post('selected') as $item)
                 {
                     $this->khacl->aro->delete($item);
                 }
                 
                 flashMsg('success',sprintf($this->lang->line('backendpro_deleted'),$this->lang->line('access_groups')));
             }
             
             // Get groups
             $data['groups'] = $this->access_control_model->getGroups();
             
             // Display Page
             $this->page->set_crumb($this->lang->line('access_groups'),'auth/admin/access/groups'); 
             $data['header'] = $this->lang->line('access_groups');
             $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/groups";
             $data['module'] = 'auth';
             $this->load->view(Site_Controller::$_container,$data);
         }
         
         /**
          * Create Group
          * 
          * @access public
          * @return void 
          */
         function create_group()
         {
            $this->load->library('validation'); 
             
            // Load Validation library at setup fields+ruels
            $fields['name'] = $this->lang->line('access_name');
            $fields['parent_name'] = $this->lang->line('access_parent');
            $rules['name'] = 'required|alpha_dash';
            $this->validation->set_fields($fields);
            $this->validation->set_rules($rules);
             
            if($this->validation->run() === FALSE)
            {
               $this->validation->output_errors();
                
               // Get possible parents
               $currgroups = $this->access_control_model->getGroups();
               if(count($currgroups)==0)
                   $data['parents'] = array('null'=>'No Parent');               
               foreach($this->access_control_model->getGroups() as $group)
               {
                   $data['parents'][$group['name_id']] = $group['name'];
               }
                
               // Display form               
               $this->page->set_crumb($this->lang->line('access_permissions'),'auth/admin/access/permissions');  
               $this->page->set_crumb($this->lang->line('access_create_group'),'auth/admin/access/create_group'); 
               $data['header'] = $this->lang->line('access_create_group');
               $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/manage_group";
               $data['module'] = 'auth';
               $this->load->view(Site_Controller::$_container,$data);
               return;
            }
            else
            {
                $this->load->module_library('auth','khacl'); 
                
                // Lets make sure the name is in the format we want
                $name = strtolower($this->input->post('name'));
                $parent = $this->input->post('parent_name');
                
                if($parent == 'null')
                    $this->khacl->aro->create($name);
                else
                    $this->khacl->aro->create($name,$parent);
                
                // Redirect back
                flashMsg("success",sprintf($this->lang->line('backendpro_created'),"Group"));
                redirect('auth/admin/access/groups','location');
            }
         }
         
         /**
          * Display Resources
          * 
          * @access public
          * @return void 
          */
         function resources()
         {
             flashMsg('info',$this->lang->line('access_table_desc'));
             
             // If items marked for delete, delete them
             if($this->input->post('delete'))
             {
                 $this->load->module_library('auth','khacl'); 
                 foreach($this->input->post('selected') as $item)
                 {
                     $this->khacl->aco->delete($item);
                 }
                 
                 flashMsg('success',sprintf($this->lang->line('backendpro_deleted'),$this->lang->line('access_resources')));
             }
             
             // Get groups
             $data['resources'] = $this->access_control_model->getResources();
             
             // Display Page
             $this->page->set_crumb($this->lang->line('access_resources'),'auth/admin/access/resources'); 
             $data['header'] = $this->lang->line('access_resources');
             $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/resources";
             $data['module'] = 'auth';
             $this->load->view(Site_Controller::$_container,$data);
         }
         
         /**
          * Create Resource
          * 
          * @access public
          * @return void 
          */
         function create_resource()
         {
            $this->load->library('validation'); 
             
            // Load Validation library at setup fields+ruels
            $fields['name'] = $this->lang->line('access_name');
            $fields['parent_name'] = $this->lang->line('access_parent');
            $rules['name'] = 'required|alpha_dash';
            $this->validation->set_fields($fields);
            $this->validation->set_rules($rules);
             
            if($this->validation->run() === FALSE)
            {
               $this->validation->output_errors();
                
               // Get possible parents
               $currresources = $this->access_control_model->getResources();
               if(count($currresources)==0)
                   $data['parents'] = array('null'=>'No Parent');               
               foreach($this->access_control_model->getResources() as $resource)
               {
                   $data['parents'][$resource['name_id']] = $resource['name'];
               }
                
               // Display form               
               $this->page->set_crumb($this->lang->line('access_permissions'),'auth/admin/access/permissions');  
               $this->page->set_crumb($this->lang->line('access_create_resource'),'auth/admin/access/create_resource'); 
               $data['header'] = $this->lang->line('access_create_resource');
               $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/manage_resource";
               $data['module'] = 'auth';
               $this->load->view(Site_Controller::$_container,$data);
               return;
            }
            else
            {
                $this->load->module_library('auth','khacl'); 
                
                // Lets make sure the name is in the format we want
                $name = strtolower($this->input->post('name'));
                $parent = $this->input->post('parent_name');
                
                if($parent == 'null')
                    $this->khacl->aco->create($name);
                else
                    $this->khacl->aco->create($name,$parent);
                
                // Redirect back
                flashMsg("success",sprintf($this->lang->line('backendpro_created'),"Resource"));
                redirect('auth/admin/access/resources','location');
            }
         }         
         
         /**
          * Display Actions
          * 
          * @access public
          * @return void 
          */
         function actions()
         {
             flashMsg('info',$this->lang->line('access_table_desc'));
             
             // If items marked for delete, delete them
             if($this->input->post('delete'))
             {
                 $this->load->module_library('auth','khacl'); 
                 foreach($this->input->post('selected') as $item)
                 {
                     $this->khacl->axo->delete($item);
                 }
                 
                 flashMsg('success',sprintf($this->lang->line('backendpro_deleted'),$this->lang->line('access_actions')));
             }
             
             // Get groups
             $query = $this->access_control_model->getActions();
             $data['actions'] = array();
             foreach($query->result() as $result)
             {
                 $tmp['id'] = $result->id;
                 $tmp['name'] = ucwords(str_replace('_',' ',$result->name));
                 $tmp['name_id'] = $result->name;
                 $data['actions'][] = $tmp;
                 unset($tmp);
             }
                          
             // Display Page
             $this->page->set_crumb($this->lang->line('access_actions'),'auth/admin/access/actions'); 
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
         function create_action()
         {
            $this->load->library('validation'); 
             
            // Load Validation library at setup fields+ruels
            $fields['name'] = $this->lang->line('access_name');
            $rules['name'] = 'required|alpha_dash';
            $this->validation->set_fields($fields);
            $this->validation->set_rules($rules);
             
            if($this->validation->run() === FALSE)
            {
               $this->validation->output_errors();
                
               // Display form               
               $this->page->set_crumb($this->lang->line('access_permissions'),'auth/admin/access/permissions');  
               $this->page->set_crumb($this->lang->line('access_create_action'),'auth/admin/access/create_action'); 
               $data['header'] = $this->lang->line('access_create_action');
               $data['page'] = $this->config->item('backendpro_template_admin') . "access_control/manage_action";
               $data['module'] = 'auth';
               $this->load->view(Site_Controller::$_container,$data);
               return;
            }
            else
            {
                $this->load->module_library('auth','khacl'); 
                
                // Lets make sure the name is in the format we want
                $name = strtolower($this->input->post('name'));
                
                $this->khacl->axo->create($name);
                
                // Redirect back
                flashMsg("success",sprintf($this->lang->line('backendpro_created'),"Action"));
                redirect('auth/admin/access/actions','location');
            }
         }
     }
?>