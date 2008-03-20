<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * Access Control Language Array
     *
     * Contains all language strings used by the Access Control Controller
     *
     * @package         BackendPro
     * @subpackage      Languages
     * @author          Adam Price
     * @copyright       Copyright (c) 2008
     * @license         http://www.gnu.org/licenses/lgpl.html
     */
  
    /* Strings used on Access Control Splash page */
    $lang['access_permissions'] = 'Permissions'; 
    $lang['access_groups'] = 'Groups'; 
    $lang['access_actions'] = 'Actions'; 
    $lang['access_resources'] = 'Resources';
    
    $lang['access_permissions_desc'] = "Through the group permissions you can specify what groups are allowed
        to access what resources, you can also specify which actions they can use 
        on these resources, e.g Create, View etc."; 
    $lang['access_groups_desc'] = "Groups allow you to create 'vitrual' containers where all the users 
        belonging to that group have something in common. For example you may have
        a group for all Administrators, Developers, Editors etc. By doing this it
        means you can assign a group a permission instead of for each indivudal user."; 
    $lang['access_actions_desc'] = "Actions are not required in the system but they let you specify an extra
        dimension to a resouce. For example say you have a news page where you users
        can add/edit/delete news. But you don't want to allow all users to delete news.
        Instead of creating a new resouce for each task, you can create actions to
        work on the resouce."; 
    $lang['access_resources_desc'] = "Resources are what you can let groups have access to. These are the
        areas you want to restrict certain people from accessing. For example
        you may have a resource for this control panel. Then using the right
        permissions can restrict only certain users to be able to use it, i.e. Administrors.";  
        
    /* General */
    $lang['access_name'] = "Name"; 
    $lang['access_parent_name'] = "Parent";
    
    /* Actions */
    $lang['access_create_action'] = "Create Action";
    $lang['access_action_created'] = "The Action '%s' has been created successfully";
    $lang['access_action_deleted'] = "The Action '%s' has been deleted successfully";
    $lang['access_action_exists'] = "Cannot add the action '%s' since it already exists!";
    $lang['access_delete_actions'] = "Are you SURE you want to delete these actions? Doing so will REMOVE all actions from related permissions";
    
    /* Permissions */
    $lang['access_permissions_table_desc'] = "Items in <font color='green'><b>green</b></font> means that group is <b>ALLOWED</b> access to it, while <font color='red'><b>red</b></font> means they are <b>DENIED</b> access to it. A resource access write takes priority over action access writes. For example if a resource is marked as <b>DENIED</b>, it dosn't matter if an action is <b>ALLOWED</b> the resource & all actions will be <b>DENIED</b>."; 
    $lang['access'] = "Access";
    $lang['access_create_permission'] = "Create Permission";
    $lang['access_edit_permission'] = "Modify Permission";
    $lang['access_allow'] = "Allow";
    $lang['access_deny'] = "Deny";
    $lang['access_advanced_permissions'] = "Advanced View Mode"; 
    $lang['access_delete_permissions'] = "Are you SURE you want to delete these permissions? WARNING: DOING SO MAY LOCK YOU OUT OF THE SYSTEM!";
    
    /* Advanced View */
    $lang['access_advanced_desc'] = "The page is used as an aid to show you how your system permissions work. Just looking at what permissions exist dosn't show you what groups have access to what resources. So select a group in the right hand tree and their resource access infomation will be shown in the middle tree. If you then click on a resource it will show you what actions the user can perform on the resource.";
    
    /* Groups */  
    $lang['access_create_group'] = "Create Group";
    $lang['access_modify_group'] = "Modify Group"; 
    $lang['access_disabled'] = "Disabled";   
    $lang['access_group_exists'] = "Cannot add the group '%s' since it already exists!";
    $lang['access_delete_groups'] = "Are you SURE you want delete these groups? Doing so will also DELETE all permissions using these resources!";
    $lang['accces_delete_default'] = "Cannot delete the group '%s' since it is the current default user group"; 
    $lang['access_parent_loop_created'] = "You cannot assign the parent of this node to be itself";      
    
    /* Resources */
    $lang['access_create_resource'] = "Create Resource"; 
    $lang['access_action_exists'] = "Cannot add the resource '%s' since it already exists!";
    $lang['access_delete_resources'] = "Are you SURE you want delete these resources? Doing so will also DELETE all permissions using these resources!";    
    
    ?>