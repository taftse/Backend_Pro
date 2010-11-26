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
 * The table prefix to use for all BackendPro tables
 */
$config['table_prefix'] = 'bep_';

/**
 * An array of all BackendPro tables used by the system
 */
$config['tables']['users'] = $config['table_prefix'] . 'users';
$config['tables']['user_profiles'] = $config['table_prefix'] . 'user_profiles';
$config['tables']['groups'] = $config['table_prefix'] . 'access_groups';
$config['tables']['resources'] = $config['table_prefix'] . 'access_resources';
$config['tables']['actions'] = $config['table_prefix'] . 'access_actions';
$config['tables']['permissions'] = $config['table_prefix'] . 'access_permissions';
$config['tables']['permission_actions'] = $config['table_prefix'] . 'access_permission_actions';
$config['tables']['settings'] = $config['table_prefix'] . 'settings';

/* End of file backendpro.php */
/* Location: ./application/backendpro_modules/core/config/backendpro.php */