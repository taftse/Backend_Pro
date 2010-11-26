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
 * BackendPro Site Controller. Sets up the BackendPro system and loads
 * any required files.
 *
 * @subpackage      Core Module
 */
abstract class Site_Controller extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->check_system_security();
        
        // Load base CodeIgniter files
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('language');

        // Load base application files
        $this->load_constants();
        $this->load->config('core/backendpro', TRUE);
        
        $this->load_exceptions();        

        // Load required modules
        $this->load->library('status/Status');
        $this->load->library('settings/Setting');
        $this->load->library('users/User');
        $this->load->library('template/Template');
        $this->load->library('asset/Asset');

        // Set the site name on the template
        $this->template->site_name = $this->setting->item('site_name');
        $this->set_variables();

        // Save the current & previous URI's
        $this->save_uris();

        log_message('debug', 'Site_Controller Controller loaded');
    }

    /**
     * Save the current & previous URI's to session so we can use them
     * for login
     *
     * @return void
     */
    private function save_uris()
    {
        $current_uri = $this->session->flashdata('current_uri');

        if($current_uri !== FALSE)
        {
            // There was a previous page before this (we know this since it is
            // in the current_uri value) so move current to previous
            $this->session->set_flashdata('previous_uri', $current_uri);
        }

        // Save the current uri
        $this->session->set_flashdata('current_uri', uri_string());
    }

    /**
     * Set site wide PHP variables to render to the page
     * 
     * @return void
     */
    private function set_variables()
    {
        $this->template->set_variable('base_url', BASE_URL);
        $this->template->set_variable('base_uri', BASE_URI);
        $this->template->set_variable('index_page', index_page());
        $this->template->set_variable('url_suffix', config_item('url_suffix'));
        $this->template->set_variable('uri_string', uri_string());
    }

    /**
     * Check System Security
     *
     * @return void
     */
    private function check_system_security()
    {
        if ($this->config->item('sess_use_database') === FALSE)
        {
            show_error("BackendPro: Your system is currently insecure. Please set 'sess_use_database' to TRUE in the main config file.");
        }

        if (count($this->config->item('encryption_key')) == 0)
        {
            show_error("BackendPro: Your system is currently insecure. Please set an 'encryption_key' in your main config file.");
        }
    }

    /**
     * Load all BackendPro exception classes
     * 
     * @return void
     */
    private function load_exceptions()
    {
        $exceptions = array('BackendProException', 'DatabaseException');

        foreach($exceptions as $exception)
        {
            list($path, $file) = Modules::find($exception, 'core', 'exceptions/');

            if (!Modules::load_file($file, $path))
            {
                show_error('Failed to load the base controller ' . $file);
            }
        }
    }

    /**
     * Load the BackendPro constants file
     * 
     * @return void
     */
    private function load_constants()
    {
        list($path, $file) = Modules::find('constants','core','config/');
        Modules::load_file($file, $path);
    }
}

/* End of file Site_controller.php */
/* Location: ./application/backendpro_modules/core/controllers/Site_controller.php */