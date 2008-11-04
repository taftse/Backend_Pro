<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro Config Array
 *
 * Contains the basic base config settings
 *
 * @package			BackendPro
 * @subpackage		Configurations
 * @author			Adam Price
 * @copyright		Copyright (c) 2008
 * @license			http://www.gnu.org/licenses/lgpl.html
 * @link            http://backendpro.kaydoo.co.uk
 */

// ---------------------------------------------------------------------------

/*
 |--------------------------------------------------------------------------
 | BackendPro Database Table Prefix
 |--------------------------------------------------------------------------
 | This is the table prefix which will be placed before
 | each table name which BackendPro uses
 */
$config['backendpro_table_prefix'] = 'be_';

/*
 |--------------------------------------------------------------------------
 | BackendPro Image Controller Settings
 |--------------------------------------------------------------------------
 | These settings relate to how the image controller will operate. You
 | can specify things like which folders to look for images in, default
 | image quality. Please see the userguide for more details.
 */
$config['backendpro_image_default_quality'] = 100;
$config['backendpro_image_folders'] = array(
	'assets/images/');

/*
 |--------------------------------------------------------------------------
 | View File Locations
 |--------------------------------------------------------------------------
 | Contains variables setting where the default view file directories are located.
 | All must be defined with trailing slashes, apart from BE_template_dir which is
 | blank by default
 */
$config['backendpro_template_dir'] = "";
$config['backendpro_template_public'] = $config['backendpro_template_dir'] . "public/";
$config['backendpro_template_admin'] = $config['backendpro_template_dir'] . "admin/";

/* End of file backendpro.php */
/* Location: system/application/config/backendpro.php */