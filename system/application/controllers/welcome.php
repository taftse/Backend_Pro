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
     * Welcome
     *
     * Post-install welcome controller
     *
     * @package         BackendPro
     * @subpackage      Controllers
     */  
    class Welcome extends Public_Controller
    {
        function Welcome()
        {
            parent::Public_Controller();
        }
        
        function index()
        {
            // Display Page
            $data['header'] = "Welcome";
            $data['page'] = $this->config->item('backendpro_template_public') . 'welcome';            
            $this->load->view($this->_container,$data);  
        }
    }
?>