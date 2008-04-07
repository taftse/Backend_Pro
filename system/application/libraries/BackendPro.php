<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * BackendPro
     *
     * A website backend system for developers for PHP 4.3.2 or newer
     *
     * @package		    BackendPro
     * @author			Adam Price
     * @copyright		Copyright (c) 2008
     * @license			http://www.gnu.org/licenses/lgpl.html
     * @link            http://www.kaydoo.co.uk/projects/backendpro
     */

     // ---------------------------------------------------------------------------

    /**
     * BackendPro
     *
     * This is the main file that does all are work for us. It controles all user authentication
     * and user functions. It also pulls together all parts of the BackendPro system, including
     * site settings, asset management, flashstatus message system, user email module and site
     * maintance control
     *
     * Please autoload this library in the CI autoload feature
     *
     * @package			BackendPro
     * @subpackage		Libraries
     */
    define('BEP_VERSION','0.2 alpha Build: 20080406');
	
    class BackendPro
	{
		/**
		 * Constructor
		 */
		function BackendPro()
		{
			// Get CI Instance
			$this->CI = &get_instance();

            /**
             * BackendPro Constants
             * 
             * BEP_TBL_PFX - BackendPro table prefix
             * ADMIN_TPL   - Admin view root folder
             * PUBLIC_TPL  - Public view root folder
             */
            define('BEP_TBL_PFX','be_');
            define('ADMIN_TPL','admin/');
            define('PUBLIC_TPL','public/');
            
			// Load base files
			//$this->CI->load->module_library('language','detect_language');		        // Load language detection
			$this->CI->load->config('backendpro');											// Load main config file
			$this->CI->lang->load('backendpro');											// Load main language file
			$this->CI->load->model('base_model');											// Load base model

			// Load site wide modules
			$this->CI->load->module_library('status','status');							    // Load status module
			$this->CI->load->module_model('preferences','preference_model','preference');	// Load site preference module
			$this->CI->load->module_library('page','page');								    // Load page_services module
			$this->CI->load->module_library('auth','userlib');								// Load authentication module

			log_message('debug','BackendPro Class Initialized');
		}
	}
?>