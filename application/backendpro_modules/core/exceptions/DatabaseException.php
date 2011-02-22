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
 * Thrown when a database error has occurred. Extends the base BackendProException
 * but does not cause a log message to be generated
 *
 * @subpackage      Core Module
 */
class DatabaseException extends BackendProException
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous, false);

        // Get the error message thrown by the database and log
        $CI = &get_instance();

        $error_msg = $CI->db->_error_message();
        $error_no = $CI->db->_error_number();

        log_message('error', 'Database error [' . $error_no . ']: ' . $error_msg);
    }
}

/* End of file DatabaseException.php */
/* Location: ./application/backendpro_modules/core/exceptions/DatabaseException.php */