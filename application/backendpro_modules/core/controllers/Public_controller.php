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
 * Frontend Controller. By using this the user dosen't have to be logged in.
 * Ideal for pages for public access.
 *
 * @subpackage      Core Module
 */
class Public_Controller extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Set the template layout
        $this->template->layout = 'public/master';
        $this->asset->group = 'public';

        log_message('debug', 'Public_Controller Controller loaded');
    }
}

/* End of file Public_Controller.php */
/* Location: ./application/backendpro_modules/core/controllers/Public_Controller.php */