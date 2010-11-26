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

// Status messages
$lang['user_login_required'] = 'You must be logged in to access this page. Please login first.';
$lang['access_denied'] = 'You do not have permission to view the page requested.';
$lang['user_saved'] = 'The user has been saved successfully';
$lang['password_reset_sent'] = 'An email containing details of how to reset your password has been sent.';
$lang['reset_key_invalid'] = 'The reset key is invalid. Please re-request your reset key.';
$lang['activation_key_invalid'] = 'The activation key is invalid or has expired.';
$lang['password_reset_saved'] = 'Your new password has been saved.';
$lang['account_activated'] = 'Your account has been activated. You can now login.';
$lang['new_account_created'] = 'Your account has been created. Please check your email for an activation email';
$lang['registration_disabled'] = 'User registration has been disabled on this site.';
$lang['invalid_login_credentials'] = 'The login credentials provided are invalid.';

// General
$lang['manage_users'] = 'Manage Users';
$lang['add_user'] = 'Add User';
$lang['edit_user'] = 'Edit User';
$lang['reset_password'] = 'Reset Password';
$lang['request_password_reset'] = 'Request Password Reset';
$lang['confirm_user_delete'] = 'Are you sure you want to delete this user?';
$lang['reset_password_description'] = 'Enter your email address and we will send you details of how to reset your password';
$lang['create_account'] = 'Create Account';
$lang['login'] = 'Login';

// Form fields
$lang['username'] = 'Username';
$lang['email'] = 'Email';
$lang['username_or_email'] = 'Username/Email';
$lang['confirm_email'] = 'Confirm Email';
$lang['password'] = 'Password';
$lang['new_password'] = 'New Password';
$lang['confirm_password'] = 'Confirm Password';
$lang['group'] = 'Group';
$lang['is_active'] = 'Is Active';
$lang['gender'] = 'Gender';
$lang['gender_unspecified'] = 'Unspecified';
$lang['gender_male'] = 'Male';
$lang['gender_female'] = 'Female';
$lang['first_name'] = 'First Name';
$lang['second_name'] = 'Second Name';
$lang['last_ip'] = 'Last IP';
$lang['last_login'] = 'Last Logged In';
$lang['created_on'] = 'Created On';
$lang['remember_me'] = 'Remember Me';

// Validation messages
$lang['validation_username_check_unique'] = 'The %s field does not contain a unique username.';
$lang['validation_email_check_unique'] = 'The %s field does not contain a unique email.';

// Email language strings
$lang['email_subject_password_change'] = 'Your password has been changed';
$lang['email_subject_new_account'] = 'New Account';
$lang['email_subject_activate_account'] = 'New Account - Activation Required';
 
/* End of users_lang.php */
/* Location: ./application/backendpro_modules/users/language/english/users_lang.php */