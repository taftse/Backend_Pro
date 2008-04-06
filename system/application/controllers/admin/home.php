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
             // Get the users notes
             
             // Construct statistics table
             /*SELECT COUNT(*) AS unactive FROM be_users WHERE active=0;
             SELECT COUNT(*) as members FROM be_users;
             SELECT value AS system_status FROM be_preferences WHERE name="maintenance_mode"
             
             SELECT * FROM 
             (SELECT COUNT(*) AS unactive FROM be_users WHERE active=0) AS unactive
             JOIN (SELECT COUNT(*) as members FROM be_users) AS members      */
             
             
             
             
             // Display Page
             $data['header'] = $this->lang->line('backendpro_dashboard');
             //$data['page'] = $this->config->item('backendpro_template_admin') . "home";
             $data['content'] = "Dashboard to come soon";
             $this->load->view(Site_Controller::$_container,$data);
         }
     }
?>