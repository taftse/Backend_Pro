<?php
	/**
	 * Install Library
	 *
	 * This file contains all classes used through the install processes.
	 *
	 * @package			BackendPro
	 * @subpackage		Installation
	 * @author			Adam Price
	 * @copyright		Copyright (c) 2008
	 * @license			http://www.gnu.org/licenses/lgpl.html
	 */

	// -------------------------------------------------------------------------

	/**
	 * Logger Class
	 *
	 * Class used to create log files and write errors to them
	 *
	 * @package 		BackendPro
	 * @subpackage		Installation
	 */
	class Logger
	{
		var $file_name  = 'install_log.txt';
		var	$date_fmt   = 'Y-m-d H:i:s';

		/**
		 * Write Log Message
		 *
		 * Write a line to the log file with the given type
		 *
		 * @param string 	Type of log to write
		 * @param string 	Message to log
		 * @return bool 	Returns TRUE on success, FALSE otherwise
		 */
		function write($type = 'INFO', $msg = NULL)
		{
			if ($msg == NULL)
			{
				return FALSE;
			}

			$type = strtoupper($type);

			// Open the log file
			if ( ! $fp = fopen($this->file_name, 'ab'))
			{
				return FALSE;
			}

			$message = $type . " " . (($type == 'INFO') ? ' - ' : '- ') . date($this->date_fmt,time()) . " --> " . $msg . "\r\n";

			flock($fp, LOCK_EX);
			fwrite($fp, $message);
			flock($fp, LOCK_UN);
			fclose($fp);

			@chmod($this->file_name, 0666);
			return TRUE;
		}
	}

	/**
 	 * Database Class
 	 *
 	 * Class used to talk to the database during installation
 	 *
 	 * @package 		BackendPro
 	 * @subpackage		Installation
 	 */
	class Database
	{
		var $connection;

		/**
		 * Connect to Database
		 *
		 * @param string		Database host machine
		 * @param string		Database name
		 * @param string		Database username
		 * @param string		Database password
		 * @return mixed		Returns string with error on fail, TRUE otherwise
		 */
		function Connect($host = NULL, $database = NULL, $user = NULL, $password = NULL)
		{
			global $logger;

			$this->connection = @mysql_connect($host,$user,$password);
	    	if ( !$this->connection)
	    	{
	    		$logger->write('error',mysql_error());
	    		return FALSE;
	    	}

		    if (! @mysql_select_db($database, $this->connection))
			{
	    		$logger->write('error',mysql_error());
	    		return FALSE;
	    	}

		    return TRUE;
		}

		/**
		 * Query
		 *
		 * Run the given query on the current connection
		 *
		 * @param string 	SQL query to execute
		 * @return bool		Returns TRUE if query executed, FALSE otherwise
	 	 */
		function Query($sql = NULL)
		{
			global $logger;

			if ($sql == NULL)
			{
				return FALSE;
			}

			if( ! @mysql_query($sql,$this->connection))
			{
				$logger->write('error',mysql_error());
				return FALSE;
			}
			return TRUE;
		}

		/**
		 * Run SQL Schema File
		 *
		 * Given a SQL Schema file process each query inside it
		 *
		 * @param string	Filename of schema file in files/ dir
		 * @return bool		Returns TRUE if all queries in file executed, FALSE otherwise
	 	 */
		function RunSQLFile($file = NULL)
		{
			global $logger;

			$path = 'files/' . $file;

			if($file == NULL)
			{
				return FALSE;
			}

		    if( !$fp = @fopen($path,'r'))
		    {
		        $logger->write('error',"Couldn't open " . $path);
		        return FALSE;
		    }

		    $contents = fread($fp, filesize($path));
		    fclose($fp);

		    // Lets get rid of comment lines
		    $contents = preg_replace('/--(.)*/','',$contents);

		    // Get rid of newlines
		    $contents = preg_replace('/\n/','',$contents);

		    // Turn each statement into an array item
		    $contents = explode(';',$contents);

		    foreach($contents as $sql)
		    {
		        if( $sql == '')
		            continue;

		        if($this->Query($sql) === FALSE)
		        {
		        	return FALSE;
		        }
		    }
		    return TRUE;
		}
	}


	/**
	 * Feature Class
	 *
	 * Allows installation features to be defined as code objects.
	 *
	 * @package 		BackendPro
	 * @subpackage 		Installation
	 */
	class Feature
	{
		var $name;								// Name of this feature
		var $components 			= array();	// Emptey list of components
		var $status 			 	= FALSE;	// Status of component installation
		var $prerequisiteFeature 	= NULL;		// Pre-requisite feature link

		function Feature($name="My Feature")
		{
			global $logger;

			$logger->write("info","New feature '" . $name . "' created");
			$this->name = $name;
		}

		/**
		 * Attach Component
		 *
		 * Attach the specified component to the feature for install
		 *
		 * @param Component Component object you want to attach
		 * @return bool		TRUE on successful attachment, FALSE otherwise
		 */
		function attach_component($component=NULL)
		{
			global $logger;

			if($component == NULL OR !is_object($component) OR strtolower(get_parent_class($component)) != "component")
				return FALSE;

			$this->components[] = &$component;
			$logger->write("info","Component '" . $component->name . "' attached to feature '" . $this->name . "'");
			return TRUE;
		}

		/**
		 * Set Prerequisite Feature
		 *
		 * Specify a prerequsite feature which must have been installed
		 * correctly for this feature to procede with installation
		 *
		 * @param Feature 	Prerequisite feature needed
		 * @return bool		TRYE on successful link, FALSE otherwise
		 */
		function set_prerequisite_feature($feature = NULL)
		{
			global $logger;

			if($feature == NULL OR !is_object($feature) OR strtolower(get_class($feature)) != "feature")
				return FALSE;

			$this->prerequisiteFeature = &$feature;
			$logger->write("info","Feature '" . $this->name . "' now has prerequisite '" . $feature->name . "'");
			return TRUE;
		}

		/**
		 * Install Feature
		 *
		 * Install the feature onto the users system
		 *
		 * @return bool		TRUE on successful install, FALSE otherwise
		 */
		function install()
		{
			global $logger;

			// First check to see if a prerequisite feature
			// failed to install
			if( $this->prerequisiteFeature != NULL && $this->prerequisiteFeature->status===FALSE)
			{
				// Can't continue so fail also
				$logger->write("info",$this->name . " Feature installation haulted since its prerequisite feature '" . $this->prerequisiteFeature->name . "' failed to install");
				return $this->status;
			}

			// Lets procede with the install of this feature
			// So for each component try and install it
			$i=0;
			while($i < count($this->components))
			{
				if( $this->components[$i]->install()===FALSE )
				{
					// Component install failed
					$logger->write("error",$this->components[$i]->name . " Component install failed: " . $this->components[$i]->error);
					break;
				}
				else
				{
					// Procede with next component
					$logger->write("info",$this->components[$i]->name . " Component installed");
					$i++;
				}
			}

			if($i != count($this->components))
			{
				$logger->write("info","Attempting to roll back Components for '" . $this->name . "' Feature");
				// Not all the components where installed
				// so run uninstall scripts for those which ran
				for($j=0;$j<=$i;$j++)
				{
					if (method_exists($this->components[$j],'Uninstall'))
					{
						if($this->components[$j]->uninstall())
						{
							$logger->write("info",$this->components[$j]->name . " Component uninstalled");
						}
						else
						{
							$logger->write("error",$this->components[$j]->name . " Component uninstall failed: " . $this->components[$j]->error);
						}
					}
				}
				return $this->status;
			}
			$this->status = TRUE;
			$logger->write('info',$this->name . " Feature installed");
			return $this->status;
		}
	}

	/**
	 * Component Class
	 *
	 * Allows a feature component to be modeled as an object
	 *
	 * @package 		BackendPro
	 * @subpackage		Installation
	 */
	class Component
	{
		var $status = FALSE;
		var $name 	= "My Component";
		var $error 	= NULL;				// Error message if thrown

		/**
		 * Install Component
		 *
		 * @return bool	Status of install
		 */
		function install()
		{
			return $this->status;
		}
	}
?>