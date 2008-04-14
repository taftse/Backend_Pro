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
     * @link            http://kaydoo.co.uk/projects/backendpro   
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
         function Home()
         {
             parent::Admin_Controller();
             log_message('debug','Home Class Initialized'); 
         }
         
         function index()
         {
             
             // Construct statistics table
             $statistics = array(
                array('name' => 'System Status', 'query' => 'SELECT value AS system_status FROM be_preferences WHERE name="maintenance_mode"'),
                array('name' => 'Site Members', 'query' => 'SELECT COUNT(*) as members FROM be_users'),
                array('name' => 'Un-active Members', 'query' => 'SELECT COUNT(*) AS unactive FROM be_users WHERE active=0')
             );
             
             // Store select statements using active record only
             
             
             /*foreach($statistics as $value)
             {
                $query = $
             }*/
             
             
             
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
             $this->load->view($this->_container,$data);
         }
     }
?>