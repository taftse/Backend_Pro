<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
    /**
     * BackendPro
     *
     * A website backend system for developers for PHP 4.3.2 or newer
     *
     * @package         BackendPro
     * @author          Adam Price
     * @copyright       Copyright (c) 2008
     * @license         http://www.gnu.org/licenses/lgpl.html
     * @link            http://backendpro.kaydoo.co.uk   
     */

     // ---------------------------------------------------------------------------

    /**
     * Home
     *
     * @package         BackendPro
     * @subpackage      Controllers
     */     
     class Home extends Admin_Controller
     {
         /**
          * Constructor
          */
         function Home()
         {
             // Call parent constructor
             parent::Admin_Controller();
             
             log_message('debug','Home Class Initialized'); 
         }
         
         function index()
         {
            // Display Page
            $data['header'] = $this->lang->line('backendpro_dashboard');
            $data['page'] = $this->config->item('backendpro_template_admin') . "home";
            $this->load->view(Site_Controller::$_container,$data);
         }
     }
?>