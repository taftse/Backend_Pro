<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 4.3.2 or newer
 *
 * @package         BackendPro
 * @author          Adam Price <adam@kaydoo.co.uk>
 * @copyright       2008-2009, Adam Price
 * @license			http://www.opensource.org/licenses/mit-license.php MIT
 * @license         http://www.gnu.org/licenses/gpl.html GPL
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

require APPPATH."third_party/MX/Controller.php";

/**
 * Load all the base BackendPro controllers
 */
load_backendpro_controller('Site_controller','core');
load_backendpro_controller('Public_controller','core');
load_backendpro_controller('Admin_controller','core');

/**
 * Load a base backendpro controller
 * 
 * @param  $controller
 * @param  $module
 * @return void
 */
function load_backendpro_controller($controller, $module)
{
    list($path, $file) = Modules::find($controller, $module, 'controllers/');

    if(!Modules::load_file($file, $path))
    {
        show_error('Failed to load the base controller ' . $file);
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */