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
 * Allows emails to be sent to users of the system
 */
class User_email
{
    public function __construct()
    {
        $CI =& get_instance();

        $CI->load->library('email');
        $CI->load->config('users/user_email', TRUE);        
        $CI->load->model('users/user_model');
    }

    /**
     * Send an email to a user of the system
     * 
     * @param mixed $id User ID or email address
     * @param string $subject Email subject
     * @param string $view View file to load
     * @param array $data
     * @return bool
     */
    public function send($id, $subject, $view, array $data = array())
    {
        $CI =& get_instance();

        if(is_numeric($id))
        {
            $user = $CI->user_model->get($id);
            $id = $user->email;
        }

        // Build email message
        $message = $CI->load->view($view, $data, TRUE);

        // Setup Email settings
        $CI->email->initialize($CI->config->item('email', 'user_email'));

        // Send email
        $CI->email->from($CI->setting->item('automated_email_address'), $CI->setting->item('automated_email_name'));
        $CI->email->to($id);
        $CI->email->subject($subject);
        $CI->email->message($message);

        return $CI->email->send();
    }
}
 
/* End of User_email.php */
/* Location: ./application/backendpro_modules/users/libraries/User_email.php */