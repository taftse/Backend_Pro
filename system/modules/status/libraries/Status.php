<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * BackendPro
     *
     * A website backend system for developers for PHP 4.3.2 or newer
     *
     * @package			BackendPro
     * @author			Adam Price
     * @copyright		Copyright (c) 2008
     * @license			http://www.gnu.org/licenses/lgpl.html
     */

     // ---------------------------------------------------------------------------

    /**
     * Status
     *
     * Allows the creation and display of status messages to the user.
     *
     * @package			BackendPro
     * @subpackage		Libraries
     */
	class Status
	{
		var $flash_var = "status";
		var $types = array('info','warning','error','success');

		function Status()
		{
			// Get CI Instance
			$this->CI = &get_instance();

			$this->CI->load->library('session');
			$this->CI->load->helper('status');

			log_message('debug','Status Class Initialized');
		}

		/**
		 * Set new status message
		 *
		 * The message will be live untill $this->display() is called
		 *
		 * @access public
		 * @param string $type Type of message to set
		 * @param string $message Message to display
		 * @return boolean
		 */
		function set($type = NULL, $message = NULL)
		{
			if ( is_null($type) OR is_null($message))
				return FALSE;

			// Check its a valid type
			if ( ! in_array($type,$this->types) )
				show_error("'".$type."' is not a valid status message type.");

			// Fetch current flashdata from session
			$data = $this->_fetch();

			// Append our message to the end
			$data[$type][] = $message;

			// Save the data back into the session
			$this->CI->session->set_userdata($this->flash_var,serialize($data));
		}

		/**
		 * Display status messages
		 *
		 * If no type has been given it will display every message,
		 * otherwise it will only show and remove that certain type of
		 * message
		 *
		 * @access public
		 * @param string $type Error type to display
		 * @param boolean $print Output to screen
		 * @return string
		 */
		function display($type = NULL,$print = TRUE)
		{
			$msgdata = $this->_fetch();

			// Output variable
			$output = "";

			if ( is_null($type)) {
				// Display all messages
				foreach ( $msgdata as $key => $mtype )
				{
					$data['messages'] = $mtype;
					$data['type'] = $key;
					$output .= $this->CI->load->view('status', $data, TRUE);
				}
			}
			else {
				// Only display messages of $type
				$data['messages'] = $msgdata[$type];
				$data['type'] = $type;
				$output =  $this->CI->load->view('status', $data, TRUE);
			}

			// Remove messages
			$this->_remove($type);

			// Print/Return output
			if ($print){
				print $output;
				return;
			}
			return $output;
		}

		/**
		 * Unset messages
		 *
		 * After a message has been shown remove it from
		 * the session data.
		 *
		 * @access private
		 * @param string $type Message type to remove
		 * @return void
		 */
		function _remove($type = NULL)
		{
			if ( is_null($type)) {
				// Unset all messages
				$this->CI->session->unset_userdata($this->flash_var);
			}
			else {
				// Unset only messages with type $type
				$data = $this->_fetch();
				unset($data[$type]);
				$this->CI->session->set_userdata($this->flash_var,serialize($data));
			}
			return;
		}

		/**
		 * Fetch flashstatus array from session
		 *
		 * @access private
		 * @return array containing the flash data
		 */
		function _fetch()
		{
			$data = $this->CI->session->userdata($this->flash_var);
			if ( empty( $data ) ) {
				return array();
			}
			else {
				return unserialize($data);
			}
		}
	}
?>