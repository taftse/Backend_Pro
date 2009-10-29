<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 4.3.2 or newer
 *
 * @package         BackendPro
 * @author          Adam Price <adam@kaydoo.co.uk>
 * @copyright       2008-2009, Adam Price
 * @license         http://www.gnu.org/licenses/lgpl.html LGPL
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

if ( ! function_exists('preference'))
{
    /**
     * Get Preference
     * 
     * @package     BackendPro
     * @subpackage  Helpers
     * @param string $name Preference to return
     * @return mixed
     */
    function preference($name)
    {
        $CI =& get_instance();
        
        return $CI->preference_model->item($name);
    }
}

/* End of file Preference_helper.php */
/* Location: ./system/application/modules/preference/helpers/Preference_helper.php */