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

class MY_Form_validation extends CI_Form_validation
{
    function run($module = '', $group = '')
    {
        (is_object($module)) AND $this->CI =& $module;
        return parent::run($group);
    }
}
 
/* End of MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */