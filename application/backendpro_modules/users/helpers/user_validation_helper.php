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

/**
 * Generate the form validation rule set for the user
 *
 * @param int $user_id User ID, only given if rule is for an update
 * @return string
 */
function get_username_rules()
{
    $min_length = setting_item('min_username_length');

    $rules[] = 'trim';
    $rules[] = 'required';
    $rules[] = 'alpha_dash';
    $rules[] = 'max_length[32]';
    $rules[] = 'min_length[' . $min_length . ']';
    $rules[] = 'callback_check_username_unique';

    return implode($rules, '|');
}

/**
 * Generate the form validation rule set for the user
 * password
 *
 * @param int $user_id User ID, only given if rule is for an update
 * @return string
 */
function get_password_rules($user_id = '')
{
    $min_length = setting_item('min_password_length');

    $rules[] = 'trim';

    // If there is no user_id we are adding a new user
    // and a password must be given
    if(!is_numeric($user_id))
    {
        $rules[] = 'required';
    }

    $rules[] = 'alpha_dash';
    $rules[] = 'max_length[32]';
    $rules[] = 'min_length[' . $min_length . ']';
    $rules[] = 'matches[confirm_password]';

    return implode($rules, '|');
}

/**
 * Generate the form validation rule set for the user email
 *
 * @parm bool $confirm Whether the email rule has a confirm_email field it must match against
 * @return string
 */
function get_email_rules($confirm = FALSE)
{
    $rules[] = 'trim';
    $rules[] = 'required';
    $rules[] = 'valid_email';

    // If there 
    if($confirm)
    {
        $rules[] = 'matches[confirm_email]';
    }

    $rules[] = 'callback_check_email_unique';

    return implode($rules, '|');
}
 
/* End of user_validation_helper.php */
/* Location: ./application/backendpro_modules/users/helpers/user_validation_helper.php */