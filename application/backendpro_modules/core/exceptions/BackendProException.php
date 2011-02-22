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
 * Thrown when an un-recoverable error is encounted. By default it logs the message
 * to file.
 *
 * @subpackage      Core Module
 */
class BackendProException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null, $log = true)
    {
        parent::__construct($message, $code, $previous);

        if($log)
        {
           log_message('error', $this->getFile() . ':' . $this->getLine() . ' - ' . $message);
        }
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

/* End of file BackendProException.php */
/* Location: ./application/backendpro_modules/core/exceptions/BackendProException.php */