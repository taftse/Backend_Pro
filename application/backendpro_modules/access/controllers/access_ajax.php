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
 * The base class for all Access ajax controllers.
 *
 * TODO: Maybe this should be a library class instead?
 *
 * @subpackage      Access Module
 */
abstract class Access_ajax extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->lang->load('access');

        // TODO: We need to perform access checks

        log_message('debug', 'Access_ajax class loaded');
    }

    /**
     * Block none ajax calls by throwing an error
     *
     * @return void
     */
    protected function block_none_ajax()
    {
       if(!$this->input->is_ajax_request())
       {
           //TODO: Uncomment me for relase show_404(uri_string() . ' can only be accessed via ajax');
       }
    }

    /**
     * Output an ajax error
     *
     * @param string $message Error message
     * @param bool $log Whether to log the error
     * @return void
     */
    protected function ajax_error($message, $log = TRUE)
    {
        if($log)
        {
            log_message('error', $message);
        }

        $this->output->set_status_header('400');
        die($message);
    }
}

/* End of file access_ajax.php */
/* Location: ./application/backendpro_modules/access/controllers/access_ajax.php */