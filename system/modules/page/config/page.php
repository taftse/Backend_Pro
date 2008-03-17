<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	/**
	 * Page Configuration Array
	 *
	 * @package			BackendPro
	 * @subpackage		Configurations
	 * @author			Adam Price
	 * @copyright		Copyright (c) 2008
	 * @license			http://www.gnu.org/licenses/lgpl.html
	 */

	/*
	|--------------------------------------------------------------------------
	| Site Asset Defaults
	|--------------------------------------------------------------------------
	| We need to set the base paths to assets. The value of $config['assets_dir']
	| should be relative to base_url ()and is assumed to be a dir. The sames goes
	| for admin/public/shared asset variables. They all should end with a trailing slash.
	| By default there is only images/css/js/icons assets
	*/
	$config['assets_dir']    = 'assets/';
	$config['asset_images']  = 'images/';
	$config['asset_css']     = 'css/';
	$config['asset_js']      = 'js/';
	$config['admin_assets']  = $config['assets_dir'] . 'admin/';
	$config['public_assets'] = $config['assets_dir'] . 'public/';
	$config['shared_assets'] = $config['assets_dir'] . 'shared/';

	/*
	|--------------------------------------------------------------------------
	| Default Assets to Load
	|--------------------------------------------------------------------------
	| Here you can specify any default css/js files you want to link into your site by default.
	| To include a shared css file call style.css the code below should be as follows:
	|
	| $config['default_assets'] = array(
	|	'admin' 		=> array(
	|		'css' 			=> array(),
	|		'js' 	  	 		=> array()),
	|	'public' 	=> array(
	|		'css' 			=> array(),
	|		'js' 	  	 		=> array()),
	|	'shared' 	=> array(
	|		'css' 			=> array('style.css'),
	|		'js' 	  	 		=> array())
	| );
	|
	| The files entered do not have to end in .js or .css. The library will only know what type
	| of file it is due to the array key the file name belongs to. These means you can include
	| .php files which dynamicly create your css files.
	| DO NOT CHANGE THE KEYS OF THE ARRAY OR ADD EXTRA ARRAY KEYS IN
	*/
	 $config['default_assets'] = array(
	    'admin'  => array(
		    'css'   => array('layout.css','style.css','navigation.css'),
			'js'    => array('navigation.js')),
		'public' => array(
			'css'   => array('layout.css'),
			'js'    => array()),
		'shared' => array(
			'css'   => array('reset.css','ie.css','typography.css','forms.css','FlashStatus.css','icons.php','treeview.css'),
			'js'    => array('jquery.js','interface.js','jquery.cookie.js','jquery.treeview.js'))
	);

	 /*
	 |--------------------------------------------------------------------------
	 | Asset Cache Length
	 |--------------------------------------------------------------------------
	 | By setting this value you can set how long you want the
	 | asset cache files to last before being refreshed. The value
	 | is in hours. Default is 0 which means the assets won't
	 | be cached.
	 */
	$config['asset_cache_length'] = 0;

	/*
	|--------------------------------------------------------------------------
	| Asset Cache File Prefix
	|--------------------------------------------------------------------------
	| Filename prefix you want the cache files to be called, please don't include extensions
	| E.g $config['asset_cache_file'] = "cache_";
	| THIS MUST NOT BE LEFT BLANK, AND BE UNIQUE TO OTHER ASSET FILES
	*/
	$config['asset_cache_file_pfx'] = "cache_";

	/*
	|--------------------------------------------------------------------------
	| Default javascript variables to assign
	|--------------------------------------------------------------------------
	| You can specify here default php variables to transfer to javascript
	| varables.
	*/
	$config['default_page_variables'] = array(
		//'base_url' => base_url()
	);
?>
