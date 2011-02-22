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

        // TODO: We need to perform access checks

        log_message('debug', 'Access Controller Loaded');
    }

    public function index()
    {
        // Get all language strings and output them to a page variable
        $this->template->set_variable('ci_language', $this->lang->language); // TODO: This is temp please see ISSUE #11

        $this->template->set_breadcrumb(lang('access_access_permissions_title'), 'access');
        $this->template->set_title(lang('access_access_permissions_title'));
        $this->template->build('admin/index');
    }
}
 
/* End of access.php */
/* Location: ./application/backendpro_modules/access/controllers/access.php */