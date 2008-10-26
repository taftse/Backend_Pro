<?php
	/**
	 * Setup FileSystem Components
	 *
	 * This file contains all the component classes for the Setup FileSystem
	 * feature. COMPONENTS ARE ONLY DEFINED HERE NOT CREATED
	 */
	
	// -------------------------------------------------------------------------

	function PerformOverWrite($fromFile, $toFile, $replacementArray)
	{
		global $logger;
		
		$file = 'files/' . $fromFile;
        $contents = file_get_contents($file);

        // Lets run our replacement
        foreach($replacementArray as $key => $value)
        {
            $contents = preg_replace('/{'.$key.'}/',$value,$contents);
        }

        // Lets copy the files to there correct locations
        if( !$fp = @fopen($toFile,'wb'))
        {
            return "Could not open " . $toFile . " to write new content to";
        }

        flock($fp, LOCK_EX);
		fwrite($fp, $contents);
		flock($fp, LOCK_UN);
		fclose($fp);
		return TRUE;
	}

	class OverWriteSystemConfig extends Component
	{
		var $name = "Create new system config file";
		var $copyFrom = "config.txt";
		var $copyTo = "/system/application/config/config.php";
		
		function Install()
		{
			// First if the user hasn't provided an encryption key
			// lets make one
			if($_POST['encryption_key'] == "")
			{
				// Base chars
			    $base = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			    $max=strlen($base)-1;
			
			    $encrypt_key='';
			    mt_srand((double)microtime()*1000000);
			    while (strlen($encrypt_key)<32)
			        $encrypt_key.=$base{mt_rand(0,$max)};
			        
			    // Save key back to POST variable
			    $_POST['encryption_key'] = $encrypt_key;
			}
			
			// Define what variables need replacing in this file
			$replace = array(
				'base_url'			=> 'http://' . $_SERVER['SERVER_NAME'] . BASEDIR . "/",
		    	'encryption_key' 	=> $_POST['encryption_key']);
			
			if ($result = PerformOverWrite($this->copyFrom,BASEPATH.$this->copyTo,$replace) !== TRUE)
			{
				$this->error = $result;
				return $this->status;
			}
			else
			{
				$this->status = TRUE;
				return $this->status;
			}
		}
	}
	
	class OverWriteDatabaseConfig extends Component
	{
		var $name = "Create new database config file";
		var $copyFrom = "database.txt";
		var $copyTo = "/system/application/config/database.php";
		
		function Install()
		{
			// Define what variables need replacing in this file
			$replace = array(
				'database_host'		=> $_POST['database_host'],
                'database_user'		=> $_POST['database_user'],
                'database_password'	=> $_POST['database_password'],
                'database_name'		=> $_POST['database_name']);
			
			if ($result = PerformOverWrite($this->copyFrom,BASEPATH.$this->copyTo,$replace) !== TRUE)
			{
				$this->error = $result;
				return $this->status;
			}
			else
			{
				$this->status = TRUE;
				return $this->status;
			}
		}
	}
	
	class OverWriteRecaptchaConfig extends Component
	{
		var $name = "Create new ReCAPTCHA config file";
		var $copyFrom = "recaptcha.txt";
		var $copyTo = "/modules/recaptcha/config/recaptcha.php";
		
		function Install()
		{
			// Define what variables need replacing in this file
			$replace = array(
				'public_key'	=> $_POST['public_key'],
                'private_key'	=> $_POST['private_key']);
			
			if ($result = PerformOverWrite($this->copyFrom,BASEPATH.$this->copyTo,$replace) !== TRUE)
			{
				$this->error = $result;
				return $this->status;
			}
			else
			{
				$this->status = TRUE;
				return $this->status;
			}
		}
	}
?>