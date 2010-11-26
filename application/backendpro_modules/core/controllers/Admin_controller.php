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
 * The Admin Controller. Using this controller will require the user
 * to be logged in and to have access to the control panel
 *
 * @subpackage      Core Module
 */
class Admin_Controller extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Check the user can access the control panel
        $this->user->has_access('Control Panel');

        // Set the layout file for the template
        $this->template->layout = 'admin/master';
        $this->asset->group = 'admin';

        $this->lang->load('core/backendpro');

        // Set the base breadcrumb link
        $this->template->set_breadcrumb(lang('control_panel'), 'admin');

        log_message('debug', 'Admin_Controller Controller loaded');
    }
}

/* End of file Admin_Controller.php */
/* Location: ./application/backendpro_modules/core/controllers/Admin_Controller.php */