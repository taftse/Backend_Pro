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
 * Provides the ability to manage the sites access permissions
 *
 * @subpackage      Access Module
 */
class Access extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->lang->load('access');

        log_message('debug', 'Access Controller Loaded');
    }

    public function index()
    {
        $this->template->set_breadcrumb(lang('access_permissions'), 'access');
        $this->template->set_title(lang('access_permissions'));
        $this->template->build('admin/index');
    }

    public function load_groups()
    {
        $this->load->model('group_model');

        try
        {
            $groups = $this->group_model->get_all();

            $json_array = array();
            foreach($groups as $group)
            {
                $json_array[$group->id] = array('name' => $group->name, 'locked' => $group->locked);
            }

            print json_encode($json_array);
        }
        catch(Exception $ex)
        {
            $this->ajax_error(lang('access_group_load_failure'));
        }
    }

    /**
     * Output an ajax error
     *
     * @param string $message Error message
     * @param bool $log Whether to log the error
     * @return void
     */
    private function ajax_error($message, $log = TRUE)
    {
        if($log)
        {
            log_message('error', $message);
        }

        $this->output->set_status_header('400');
        print $message;
        exit;
    }
}
 
/* End of access.php */
/* Location: ./application/backendpro_modules/access/controllers/access.php */