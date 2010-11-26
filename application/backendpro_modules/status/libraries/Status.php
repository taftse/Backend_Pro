<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 5.2.6 or newer
 *
 * @package         BackendPro
 * @author          Adam Price <adam@kaydoo.co.uk>
 * @copyright       2008-2010, Adam Price
 * @license			http://www.opensource.org/licenses/mit-license.php MIT
 * @license         http://www.gnu.org/licenses/gpl.html GPL
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

/**
 * The status library provides the ability to save status messages
 * and display the status message back to the user.
 *
 * @subpackage      Status Module
 */
class Status
{
    /**
     * Holds the session variable name to use to save
     * all status messages to
     *
     * @var string
     */
    private $session_variable;

    /**
     * Allowed message types
     * 
     * @var array
     */
    private $message_types = array();

    public function __construct()
    {
        $CI = &get_instance();

        // Load required CodeIgniter files
        $CI->load->library('session');
        $CI->load->helper('language');

        // Load other module files
        $CI->lang->load('status/status');

        // Load the configuration values
        $this->load_config();

        log_message('debug', 'Status Library loaded');
    }

    /**
     * Load the config options from file
     * 
     * @return void
     */
    private function load_config()
    {
        $CI = &get_instance();

        $CI->config->load('status/status', TRUE);

        $this->session_variable = $CI->config->item('flash_variable','status');
        $this->message_types = $CI->config->item('message_types','status');
    }

    /**
	 * Set a new status message
	 *
	 * @param string $type Type of message
	 * @param string $message Message to display
	 * @return void
	 */
	public function set($type, $message)
	{
        if(is_array($message))
        {
            foreach($message as $msg)
            {
                $this->set($type, $msg);
            }
            return TRUE;
        }

		$CI = &get_instance();

		// Check its a valid type
		if (!in_array($type, $this->message_types) )
		{
			log_message('error','BackendPro: Invalid status message type: ' . $type);
			return FALSE;
		}

		// Fetch current messages from session
		$data = $this->fetch_messages();

		// Convert the language string if one is given
		$message = translate_lang($message);

		// Append our message to the end if not already created
		if(!array_key_exists($type, $data) OR !in_array($message,$data[$type]))
		{
			$data[$type][] = $message;

			// Save the data back into the session
			$CI->session->set_userdata($this->session_variable, serialize($data));
		}

		return TRUE;
	}

	/**
	 * Display status messages
	 *
	 * Get either a type of status message or all messages
	 *
	 * @param string $type Message type to display
     * @param bool $output Output to browser
	 * @return string
	 */
	public function display($type = NULL, $output = TRUE)
	{
		$CI = &get_instance();

        // Get all current messages
		$messages = $this->fetch_messages();

        // Get the base controller name
        $controller_name = $this->get_base_controller_name();

        $output = NULL;

        if ($type == NULL)
        {
            // Display all messages
            foreach ( $messages as $type => $message )
            {
                $data['messages'] = $message;
                $data['type'] = $type;
                $output .= $CI->load->view('status/' . $controller_name, $data, TRUE);
            }
        }
        else
        {
            // Only display messages of $type
            $data['messages'] = $messages[$type];
            $data['type'] = $type;
            $output =  $CI->load->view('status/' . $controller_name, $data, TRUE);
        }

        // Remove all messages of the type we just displayed
        $this->remove($type);

        return $output;
	}

    /**
     * Get the base controller name for the current page. We will use this to
     * try and find a matching view
     *
     * @return string
     */
    private function get_base_controller_name()
    {
        $CI = &get_instance();

        // Get the current controller name
        $name = $CI->router->fetch_class();

        // Now get its parent name
        $name = get_parent_class($name);

        // Make sure it exists as a view
        Modules::find($name, 'status', 'views/');

        return $name;
    }

	/**
	 * Unset messages
	 *
	 * Remove messages from session.
	 *
	 * @param string $type Message type
	 * @return void
	 */
	private function remove($type = NULL)
	{
		$CI = &get_instance();

		if($type == NULL)
		{
			// Unset all messages
			$CI->session->unset_userdata($this->session_variable);
		}
		else
		{
			// Unset only messages with type $type
			$data = $this->fetch_messages();
			unset($data[$type]);
			$CI->session->set_userdata($this->session_variable, serialize($data));
		}
	}

	/**
	 * Fetch status message from session
	 *
	 * @return array
	 */
	private function fetch_messages()
	{
		$CI = &get_instance();

		$data = $CI->session->userdata($this->session_variable);

		if (empty($data))
		{
			return array();
		}
		else
		{
			return unserialize($data);
		}
	}
}

/* End of file Status.php */
/* Location: ./application/backendpro_modules/status/libraries/Status.php */