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

$route['users/save'] = 'manage/save';
$route['users/add'] = 'manage/add';
$route['users/edit/(:any)'] = 'manage/edit/$1';
$route['users/delete/(:any)'] = 'manage/delete/$1';

$route['users/reset/request'] = 'reset/request';
$route['users/reset/(:any)'] = 'reset/reset/$1'; 
 
/* End of routes.php */
/* Location: ./application/backendpro_modules/users/config/routes.php */