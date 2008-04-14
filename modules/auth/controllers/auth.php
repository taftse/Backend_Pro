<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * BackendPro
     *
     * A website backend system for developers for PHP 4.3.2 or newer
     *
     * @package			BackendPro
     * @author				Adam Price
     * @copyright			Copyright (c) 2008
     * @license				http://www.gnu.org/licenses/lgpl.html
     * @tutorial				BackendPro.pkg
     */

     // ---------------------------------------------------------------------------

    /**
     * Auth
     *
     * Authentication Controller
     *
     * @package			BackendPro
     * @subpackage		Controllers
     */
	class Auth extends Public_Controller
	{
		/**
		 * Constructor
		 */
		function Auth()
		{
			// Call parent constructor
			parent::Public_Controller();
            
			log_message('debug','Auth Class Initialized');
		}

        function index()
        {
            $this->login();
        }
        
		function login()
		{
            $this->userlib->login_form($this->_container);
		}
        
        function logout()
        {
            $this->userlib->logout();
        }
        
        function forgotten_password()
        {
            $this->userlib->forgotten_password_form($this->_container);
        }
        
        function register()
        {
            $this->userlib->register_form($this->_container);
        }
        
        function activate()
        {
            $this->userlib->activate();
        }
	}
?>