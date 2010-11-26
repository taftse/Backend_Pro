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
 * Fetch a setting value from the database
 * 
 * @param string $slug Setting slug
 * @return mixed
 */
function setting_item($slug)
{
    $CI =& get_instance();

    return $CI->setting->item($slug);
}
 
/* End of setting_helper.php */
/* Location: ./application/backendpro_modules/settings/helpers/setting_helper.php */