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

$route['settings/delete/(:any)'] = 'manage/delete/$1';

$route['settings/edit/(:any)/save'] = 'manage/save';
$route['settings/edit/(:any)'] = 'manage/edit/$1';

$route['settings/add/save'] = 'manage/save';
$route['settings/add'] = 'manage/add';

$route['settings/save'] = 'settings/save';
 
/* End of routes.php */
/* Location: ./application/backendpro_modules/settings/config/routes.php */