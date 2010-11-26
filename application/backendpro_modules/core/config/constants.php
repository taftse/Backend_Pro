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

define('BEP_VERSION','1.0');

/*
|--------------------------------------------------------------------------
| URL Redirect Method
|--------------------------------------------------------------------------
|
| Sets which redirect method should be used for redirect(). Valid options
| are:
| - location
| - refresh
|
*/
define('REDIRECT_METHOD', 'location');

/*
|--------------------------------------------------------------------------
| Base URL/URI
|--------------------------------------------------------------------------
|
| The following code is taken from PryoCMS by Phil Sturgeon
| (http://philsturgeon.co.uk/)
|
*/

// Base URL (keeps this crazy sh*t out of the config.php
if(isset($_SERVER['HTTP_HOST']))
{
        $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
        $base_url .= '://'. $_SERVER['HTTP_HOST'];
        $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        // Base URI (It's different to base URL!)
        $base_uri = parse_url($base_url, PHP_URL_PATH);
        if(substr($base_uri, 0, 1) != '/') $base_uri = '/'.$base_uri;
        if(substr($base_uri, -1, 1) != '/') $base_uri .= '/';
}
else
{
        $base_url = 'http://localhost/';
        $base_uri = '/';
}

// Define these values to be used later on
define('BASE_URL', $base_url);
define('BASE_URI', $base_uri);
define('APPPATH_URI', BASE_URI . APPPATH);

// We don't need these variables any more
unset($base_uri, $base_url);
 
/* End of constants.php.php */
/* Location: ./application/backendpro_modules/core/config/constants.php.php */