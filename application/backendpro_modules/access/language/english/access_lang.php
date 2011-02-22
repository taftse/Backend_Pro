<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 5.2.6 or newer
 *
 * @package         BackendPro
 * @author          Adam Price <adam@kaydoo.co.uk>
 * @copyright       2008-2010, Adam Price
 * @license         http://www.opensource.org/licenses/mit-license.php MIT
 * @license         http://www.gnu.org/licenses/gpl.html GPL
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

/* ----- Titles ----- */
$lang['access_access_permissions_title'] = 'Access Permissions';
$lang['access_select_group_title'] = 'Select Group';
$lang['access_select_resource_title'] = 'Select Resource';
$lang['access_select_actions_title'] = 'Select Actions';

/* ----- Group ----- */
$lang['access_group_load_failure'] = 'Failed to load the groups';
$lang['access_invalid_group_id'] = 'The Group Id given is either undefined or is not numeric';
$lang['access_add_group'] = 'Add';
$lang['access_edit_group'] = 'Edit';
$lang['access_delete_group'] = 'Delete';
$lang['access_group_prompt'] = 'Please enter the Group name:';
$lang['access_unable_to_save_group'] = 'Unable to save the group details';
$lang['access_group_not_found'] = 'The group specified was not found';

/* ----- Resource ----- */
$lang['access_resource_load_failure'] = 'Failed to load the resources';
$lang['access_invalid_resource_id'] = 'The Resource Id given is either undefined or is not numeric';
$lang['access_confirm_resource_permission_revoke'] = 'You are about to remove permission for the %s group to access the %s resource. This will also remove all permissions to descendant resources. Are you sure you want to continue?';
$lang['access_add_resource'] = 'Add';
$lang['access_edit_resource'] = 'Edit';
$lang['access_delete_resource'] = 'Delete';
$lang['access_resource_prompt'] = 'Please enter the Resource name:';
$lang['access_unable_to_save_resource'] = 'Unable to save the resource details';
$lang['access_invalid_resource_save_parameters'] = 'When saving a resource an Id or Parent Id must be given';
$lang['access_resource_not_found'] = 'The resource specified was not found';

/* ----- Action ----- */
$lang['access_action_load_failure'] = 'Failed to load the actions';
$lang['access_all_actions'] = 'All Actions';
$lang['access_view_action'] = 'View';
$lang['access_add_action'] = 'Add';
$lang['access_edit_action'] = 'Edit';
$lang['access_delete_action'] = 'Delete';
$lang['access_action_prompt'] = 'Please enter the Action name:';
$lang['access_unable_to_save_action'] = 'Unable to save the action details';
$lang['access_invalid_action_save_parameters'] = 'When saving an action an Id or Resource Id must be given';
$lang['access_action_not_found'] = 'The action specified was not found';

/* ----- Misc ----- */
$lang['access_server_timeout'] = 'The server has timed out, please try again.';
$lang['access_invalid_permission'] = "Invalid permission, only 'allow' and 'deny' are valid";
$lang['access_unable_to_change_permission'] = 'Unable to save the new permission value for the selected resource/action';
$lang['access_unknown_action'] = 'The action `%s` is invalid and is unknown';
$lang['access_unknown_section'] = 'The section `%s` is invalid and is unknown';
$lang['access_invalid_id'] = 'The id `%s` must be defined and be a number';
$lang['access_invalid_value'] = 'The value `%s` must be defined and a string';
$lang['access_unable_to_delete_item'] = 'Unable to delete the %s';
$lang['access_unable_to_modify_locked_item'] = 'Unable to modify a locked item';
$lang['access_confirm_delete'] = 'Are you sure you want to delete this %s?';

//* ----- Validation ----- */
$lang['access_failed_to_validate'] = 'An error occurred during validation of the data';
$lang['access_validation_group_name_required'] = 'The Group name is required';
$lang['access_validation_group_name_min_length'] = 'The Group name must be at least %s characters in length';
$lang['access_validation_group_name_max_length'] = 'The Group name can not exceed %s characters in length';
$lang['access_validation_group_name_unique'] = 'The Group name `%s` already exists. Please enter another name';

$lang['access_validation_resource_name_required'] = 'The Resource name is required';
$lang['access_validation_resource_name_min_length'] = 'The Resource name must be at least %s characters in length';
$lang['access_validation_resource_name_max_length'] = 'The Resource name can not exceed %s characters in length';
$lang['access_validation_resource_name_unique'] = 'The Resource name `%s` already exists. Please enter another name';

$lang['access_validation_action_name_required'] = 'The Action name is required';
$lang['access_validation_action_name_min_length'] = 'The Action name must be at least %s characters in length';
$lang['access_validation_action_name_max_length'] = 'The Action name can not exceed %s characters in length';
$lang['access_validation_action_name_unique'] = 'The Action name `%s` already exists. Please enter another name';
 
/* End of access_lang.php */
/* Location: ./application/backendpro_modules/access/language/english/access_lang.php */