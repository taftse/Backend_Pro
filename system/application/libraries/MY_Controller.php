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
     * Site_Controller
     *
     * Extends the default CI Controller class so I can declare special site controllers
     *
     * @package         BackendPro
     * @subpackage      Controllers
     */
    class Site_Controller extends Controller
    {
        static $_container;
        /**
        * Constructor
        */
        function Site_Controller()
        {
            // Call parent constructor
            parent::Controller();
        
            // Display page debug messages if needed
            /*if ( is_superadmin() AND $this->preference->item('page_debug'))
            {
                $this->output->enable_profiler(TRUE);
            }*/
        
            log_message('debug','Site_Controller Class Initialized');
        }
    }
    
    /**
    * Public_Controller
    *
    * Extends the Site_Controller class so I can declare special Public controllers
    *
    * @package		BackendPro
    * @subpackage		Controllers
    */
    class Public_Controller extends Site_Controller
    {                                 
	    /**
	     * Constructor
	     */
	    function Public_Controller()
	    {
		    // Call parent constructor
		    parent::Site_Controller();
            
            // Set container variable
            Site_Controller::$_container = $this->config->item('backendpro_template_public') . "container.php";            
            
            // Check whether to show the site offline message
            /*if( $this->preference->item('maintenance_mode') AND !is_superadmin() AND $this->uri->rsegment(1) != 'auth')
            {
                redirect('auth/maintenance','location');
            }*/
            
		    log_message('debug','Public_Controller Class Initialized');
	    }
        
        /**
        * Maintenance Message
        * 
        * Dispaly the maintenance message
        * 
        * @access public
        * @return void
        */
        function maintenance()
        {
            // Display Maintenance message
            $data['header'] = $this->lang->line('backendpro_maintenance');
            $data['message'] = $this->preference->item('maintenance_message');
            $data['page'] = $this->config->item('backendpro_template_public') . "under_maintenance";
            $this->load->view(Site_Controller::$_container,$data);
        }
    }

    /**
     * Admin_Controller
     *
     * Extends the Site_Controller class so I can declare special Admin controllers
     *
     * @package			BackendPro
     * @subpackage		Controllers
     */
	class Admin_Controller extends Site_Controller
	{
		/**
		 * Constructor
		 */
		function Admin_Controller()
		{
			// Call parent constructor
			parent::Site_Controller();

			// Set base crumb
			$this->page->set_crumb($this->lang->line('backendpro_control_panel'),'admin');

			// Set container variable
			Site_Controller::$_container = $this->config->item('backendpro_template_admin') . "container.php";

            // Make sure user is logged in
            //check('Control Panel');
            
            // If the system is down display warning
            if($this->preference->item('maintenance_mode'))
                flashMsg('warning',$this->lang->line('backendpro_in_maintenance_mode'));
            
			log_message('debug','Admin_Controller Class Initialized');
		}
    }
?>