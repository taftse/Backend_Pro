<?php
/**
 * BackendPro
 *
 * An open source development control panel written in PHP
 *
 * @package		BackendPro
 * @author		Adam Price
 * @copyright	Copyright (c) 2008, Adam Price
 * @license		http://www.gnu.org/licenses/lgpl.html
 * @link		http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

// ------------------------------------------------------------------------

include_once('common/Logger.php');
include_once('common/Database.php');
include_once('common/Feature.php');
include_once('common/Component.php');

// Setup a logger
$logger = new Logger();

// Setup the database connector
$database = new Database();

// Define the base path of the CI installation
// this should be relative to the components folder
define('BASEPATH','./..');
define('BASEDIR',dirname(dirname($_SERVER['PHP_SELF'])));
$logger->write('info','Basepath set to ' . BASEPATH);

// Define Install components
$features['writable_check'] = new Feature("FileSystem Check");
$features['copy_files'] = new Feature("Setup FileSystem");
$features['database_setup'] = new Feature("Setup Database");

// Setup any prerequisites
$features['copy_files']->set_prerequisite_feature(&$features['writable_check']);
$features['database_setup']->set_prerequisite_feature(&$features['copy_files']);

// Load component libraies
include_once("components/FileSystemCheck.php");
include_once("components/SetupFileSystem.php");
include_once("components/SetupDatabase.php");

// Associate components to filesystem check feature
$features['writable_check']->attach_component(new LogFolderWritable());
$features['writable_check']->attach_component(new AssetFoldersWritable());
$features['writable_check']->attach_component(new CacheFolderWritable());
$features['writable_check']->attach_component(new ConfigFilesWritable());

// Associate components to Setup Filesystem feature
$features['copy_files']->attach_component(new OverWriteSystemConfig());
$features['copy_files']->attach_component(new OverWriteDatabaseConfig());
$features['copy_files']->attach_component(new OverWriteRecaptchaConfig());

// Associate components to Setup Database Feature
$features['database_setup']->attach_component(new ConnectToDatabase());
$features['database_setup']->attach_component(new UpdateSchema());
$features['database_setup']->attach_component(new CreateAdministrator());

// Perform the install
$install_status = TRUE;
foreach($features as $key => $feature)
{
	// We need to do this since php4 dosn't support reference in forloops
	$block =& $features[$key];
	if ($block->install() === FALSE)
	{
		$install_status = FALSE;
	}
}

/* End of file RUN.php */
/* Location: ./install/RUN.php */