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
$lang['users_manage_title'] = 'Manage Users';
$lang['users_add_user_title'] = 'Add User';
$lang['users_edit_user_title'] = 'Edit %s';
$lang['users_login_title'] = 'Login';
$lang['users_request_reset_title'] = 'Request Password Reset';
$lang['users_reset_title'] = 'Reset Password';
$lang['users_register_title'] = 'Register New Account';

/* ----- Form Field Labels ----- */
$lang['users_username_label'] = 'Username';
$lang['users_email_label'] = 'Email';
$lang['users_username_or_email_label'] = 'Username/Email';
$lang['users_confirm_email_label'] = 'Confirm Email';
$lang['users_password_label'] = 'Password';
$lang['users_new_password_label'] = 'New Password';
$lang['users_confirm_password_label'] = 'Confirm Password';
$lang['users_group_label'] = 'Group';
$lang['users_is_active_label'] = 'Is Active';

$lang['users_last_ip_label'] = 'Last IP';
$lang['users_last_login_label'] = 'Last Logged In';
$lang['users_created_on_label'] = 'Created On';
$lang['users_remember_me_label'] = 'Remember Me';

/* ----- Links/Buttons ----- */
$lang['users_add_link'] = 'Add User';
$lang['users_delete_link'] = 'Delete';
$lang['users_save_changes_btn'] = 'Save Changes';
$lang['users_login_btn'] = 'Login';
$lang['users_reset_link'] = 'Forgotten Password?';
$lang['users_reset_btn'] = 'Submit Request';
$lang['users_save_password_btn'] = 'Save New Password';
$lang['users_register_link'] = 'Create an Account now';
$lang['users_register_btn'] = 'Create Account';

/* ----- Form Validation Error Messages ----- */
$lang['users_validation_username_check_unique'] = 'The %s field does not contain a unique username.';
$lang['users_validation_email_check_unique'] = 'The %s field does not contain a unique email.';

/* ----- Status Messages ----- */

// Login
$lang['users_invalid_login_credentials'] = 'The login credentials provided are invalid.';
$lang['users_login_required'] = 'You must be logged in to access this page. Please login first.';
$lang['users_access_denied'] = 'You do not have permission to view the page requested.';

// Password Reset
$lang['users_password_reset_sent'] = 'An email containing details of how to reset your password has been sent.';
$lang['users_reset_key_invalid'] = 'The reset key is invalid. Please re-request your reset key.';
$lang['users_password_reset_saved'] = 'Your new password has been saved.';

// Account Activation
$lang['users_account_activated'] = 'Your account has been activated. You can now login.';
$lang['users_activation_key_invalid'] = 'The activation key is invalid or has expired.';

// Registration
$lang['users_new_account_created'] = 'Your account has been created. Please check your email for an activation email';
$lang['users_registration_disabled'] = 'User registration has been disabled on this site.';

// Other
$lang['users_changes_saved'] = 'The user has been saved successfully';
$lang['users_user_not_found'] = 'The user was not found';
$lang['users_cannot_delete_yourself'] = 'You cannot delete yourself.';
$lang['users_user_deleted'] = 'The user has been deleted successfully';

/* ----- Email ----- */
$lang['users_email_subject_password_change'] = 'Your password has been changed';
$lang['users_email_subject_new_account'] = 'New Account';
$lang['users_email_subject_activate_account'] = 'New Account - Activation Required';
$lang['users_email_subject_reset_password'] = 'Reset Password Request';

/* ---- Misc ----- */
$lang['users_confirm_delete'] = 'Are you sure you want to delete this user?';
$lang['users_reset_password_description'] = 'Enter your email address and we will send you details of how to reset your password';

// User Listing
$lang['users_no_last_login'] = 'Never';
$lang['users_no_last_ip'] = 'None';
$lang['users_is_active_yes'] = 'Yes';
$lang['users_is_active_no'] = 'No';

/* End of users_lang.php */
/* Location: ./application/backendpro_modules/users/language/english/users_lang.php */