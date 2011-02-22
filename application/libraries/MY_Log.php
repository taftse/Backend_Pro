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
 
class MY_Log extends CI_Log
{
    protected $sub_levels = array('backendpro');
    
    public function __construct()
    {
        parent::__construct();

        // TODO: Try to load the sub-levels from config otherwise just act like normal
    }

    function write_log($level = 'error', $msg, $php_error = FALSE)
    {
        $sublevels = explode(':', $level, 2);

        if ($sublevels[0] != 'debug')
        {
            // We must have an original level (excluding debug) just log it
            parent::write_log($sublevels[0], $msg, $php_error);
        }
        else
        {
            if ( in_array('debug', $this->sub_levels) || empty($this->sub_levels))
            {
                // We have specifically been told to log debug messages
                parent::write_log($sublevels[0], $msg, $php_error);
            }
            else if (isset($sublevels[1]) && in_array($sublevels[1], $this->sub_levels))
            {
                // We have been told to log our specific sub-level
                parent::write_log($sublevels[0], $msg, $php_error);
            }
        }
    }
}

/* End of file MY_Log.php */
/* Location: ./application/libraries/MY_Log.php */