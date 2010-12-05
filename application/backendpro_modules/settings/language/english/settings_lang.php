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
$lang['settings_title'] = 'Settings';
$lang['settings_edit_setting_title'] = 'Edit %s Setting';
$lang['settings_add_setting_title'] = 'Add Setting';

/* ----- Form Field Labels ----- */
$lang['settings_slug_label'] = 'Slug';
$lang['settings_title_label'] = 'Title';
$lang['settings_description_label'] = 'Description';
$lang['settings_type_label'] = 'Type';
$lang['settings_value_label'] = 'Value';
$lang['settings_options_label'] = 'Options';
$lang['settings_validation_rules_label'] = 'Rules';
$lang['settings_is_required_label'] = 'Is Required';
$lang['settings_is_gui_label'] = 'Is GUI';
$lang['settings_module_label'] = 'Module';

/* ----- Links/Buttons ----- */
$lang['settings_add_link'] = 'Add';
$lang['settings_edit_link'] = 'Edit';
$lang['settings_delete_link'] = 'Delete';
$lang['settings_save_link'] = 'Save Changes';

/* ----- Form Validation Error Messages ----- */
$lang['settings_validation_options_check_required'] = 'The %s field cannot be empty when the type is set to select.';
$lang['settings_validation_options_check_invalid'] = 'The %s field does not contain a valid comma separated list.';
$lang['settings_validation_type_check'] = 'The %s field does not contain a valid control type.';
$lang['settings_validation_unique_slug'] = 'The %s field must contain a unique value';

/* ----- Status Messages ----- */
$lang['settings_changes_saved'] = 'Settings have been saved successfully';
$lang['settings_setting_not_found'] = 'The setting was not found';
$lang['settings_cannot_delete_locked_setting'] = 'You cannot delete a setting which is locked';
$lang['settings_an_error_occurred'] = 'An error occurred';

/* ---- Misc ----- */
$lang['settings_confirm_delete'] = 'Are you sure you want to delete this setting?';
$lang['settings_unknown_control_type'] = '%s is an unknown setting control type, cannot render';
$lang['settings_illegal_access_to_delete_page'] = 'You cannot delete a setting via direct browser access';

/* End of settings_lang.php */
/* Location: ./application/backendpro_modules/settings/language/english/settings_lang.php */