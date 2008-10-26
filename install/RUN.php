<?php
	/**
	 * RUN THE BACKENDPRO INSTALL PROCESS
	 */
	include_once('install_library.php');
	$logger = new Logger();
	$database = new Database();
	
	// Define the base path of the CI installation
	// this should relative to the components folder
	define('BASEPATH','./..');
	define('BASEDIR',dirname(dirname($_SERVER['PHP_SELF'])));
	$logger->write('info','Basepath set to ' . BASEPATH);

	/**
	 * DEFINE INSTALL FEATURES
	 */
	$features['writable_check'] = new Feature("FileSystem Check");
	$features['copy_files'] = new Feature("Setup FileSystem");
	$features['database_setup'] = new Feature("Setup Database");
	
	// Make sure 'Setup Custom Filesystem has a prerequiste that the file system
	// is writable
	$features['copy_files']->set_prerequisite_feature(&$features['writable_check']);
	$features['database_setup']->set_prerequisite_feature(&$features['copy_files']);
	
	// Load component libraies
	include_once("components/FileSystemCheck.php");
	include_once("components/SetupFileSystem.php");
	include_once("components/SetupDatabase.php");
	
	/*
	 * ASSOCIATE COMPONENTS TO FILESYSTEM CHECK FEATURE
	 */
	$features['writable_check']->attach_component(new LogFilesWritable());
	$features['writable_check']->attach_component(new AssetFoldersWritable());
	$features['writable_check']->attach_component(new ConfigFilesWritable());
	
	/*
	 * ASSOCIATE COMPONENTS TO FILESYSTEM CHECK FEATURE
	 */
	$features['copy_files']->attach_component(new OverWriteSystemConfig());
	$features['copy_files']->attach_component(new OverWriteDatabaseConfig());
	$features['copy_files']->attach_component(new OverWriteRecaptchaConfig());
	
	/*
	 * ASSOCIATE COMPONENTS TO FILESYSTEM CHECK FEATURE
	 */
	$features['database_setup']->attach_component(new ConnectToDatabase());
	$features['database_setup']->attach_component(new UpdateSchema());
	$features['database_setup']->attach_component(new CreateAdministrator());
	
	/*
	 * PERFORM THE INSTALLATION
	 */
	$install_status = TRUE;
	foreach($features as $key => $feature)
	{
		$block =& $features[$key];			// We need to do this since php4 dosn't support reference in forloops
		if ($block->install() === FALSE)
			$install_status = FALSE;
	}
?>