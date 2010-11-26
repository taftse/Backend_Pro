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
 * The flash variable is the session flashdata variable
 * which is used to save all status messages to.
 */
$config['flash_variable'] = 'status_messages';

/**
 * A list of allowed message types which can be stored using
 * the status class
 */
$config['message_types'] = array('notice','info','warning','error','success');

/* End of file status.php */
/* Location: ./application/backendpro_modules/status/config/status.php */