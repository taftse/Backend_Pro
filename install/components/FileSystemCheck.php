<?php
	/**
	 * FileSystem Check Components
	 *
	 * This file contains all the component classes for the FileSystem Check
	 * feature. COMPONENTS ARE ONLY DEFINED HERE NOT CREATED
	 */
	
	// -------------------------------------------------------------------------

	/**
	 * Log files are writable
	 *
	 * Check the log files folder is writable
	 */
	class LogFilesWritable extends Component
	{
		var $name = "Log files writable";
		var $path = "/system/logs";
		
		function install()
		{
			if( is_writeable(BASEPATH.$this->path))
				$this->status = TRUE;
			else
				$this->error = $this->path . " folder isn't writable";
				
			return $this->status;
		}
	}
	
	/**
	 * Asset folders are writable
	 *
	 * Check all the asset folders are writable for the page
	 * class to be able to write to them
	 */
	class AssetFoldersWritable extends Component
	{
		var $name = "Asset folders writable";
		var $path_array = array(
			'/assets/admin/css',
			'/assets/admin/js',
			'/assets/public/css',
			'/assets/public/js',
			'/assets/shared/css',
			'/assets/shared/js');
		
		function install()
		{
			foreach($this->path_array as $path)
			{
				if ( !is_writable(BASEPATH.$path))
				{
					$this->error = $path . " folder isn't writable";
					return $this->status;
				}
			}
			$this->status = TRUE;
			return $this->status;
		}
	}
	
	/**
	 * Config files are writable
	 *
	 * Check all config files we need to write to later
	 * are writable
	 */
	class ConfigFilesWritable extends Component
	{
		var $name = "Config files writable";
		var $file_array = array(
			'/system/application/config/config.php',
			'/system/application/config/database.php',
			'/modules/recaptcha/config/recaptcha.php');
		
		function install()
		{
			foreach($this->file_array as $file)
			{
				if ( !is_writable(BASEPATH.$file))
				{
					$this->error = $file . " file isn't writable";
					return $this->status;
				}
			}
			$this->status = TRUE;
			return $this->status;
		}
	}
?>