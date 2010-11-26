<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 5.2.6 or newer
 *
 * @package         BackendPro
 * @author          Adam Price <adam@kaydoo.co.uk>
 * @copyright       2008-2010, Adam Price
 * @license            http://www.opensource.org/licenses/mit-license.php MIT
 * @license         http://www.gnu.org/licenses/gpl.html GPL
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

/**
 * Determines if the last crumb in the breadcrumb trail
 * should be shown as a clickable link.
 *
 * If TRUE and the final crumb has a URI given then following will be outputted
 * <a>First</a> > <a>Second</a>
 *
 * If FALSE
 * <a>First</a> > Second
 */
$config['display_final_link'] = FALSE;

/**
 * The string separator to use to split up the breadcrumb
 * links. This is inserted between each outputted crumb.
 */
$config['breadcrumb_separator'] = ' &gt; ';

/**
 * The page title separator string. This is used to split the page
 * name and site name (if set).
 *
 * For example:
 * My Page | BackendPro.co.uk
 */
$config['title_separator'] = ' | ';

/* End of template.php */
/* Location: ./application/backendpro_modules/template/config/template.php */