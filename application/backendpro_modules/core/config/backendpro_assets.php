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

$config['assets']['public'][] = 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js';
$config['assets']['public'][] = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js';

$config['assets']['public'][] = 'css/backendpro/reset.css';
$config['assets']['public'][] = 'css/backendpro/layout.css';
$config['assets']['public'][] = 'css/backendpro/style.css';
$config['assets']['public'][] = 'css/backendpro/buttons.css';
$config['assets']['public'][] = 'css/backendpro/forms.css';
$config['assets']['public'][] = 'css/backendpro/jquery-ui.css';

$config['assets']['admin'] = $config['assets']['public'];

$config['assets']['admin'][] = 'css/backendpro/row-actions.css';            // Used on Users page

$config['assets']['admin'][] = 'js/codeigniter.js';                         // User throughout the admin area

$config['assets']['admin'][] = 'js/sprintf-0.7-beta1.js';                   // Used on the access page
$config['assets']['admin'][] = 'css/backendpro/jquery.contextMenu.css';     // Used on the access page
$config['assets']['admin'][] = 'js/jquery.contextMenu.js';                  // Used on the access page
$config['assets']['admin'][] = 'js/backendpro/access_permissions.js';       // Used on the access page

/* End of backendpro_assets.php */
/* Location: ./application/backendpro_modules/core/config/backendpro_assets.php */