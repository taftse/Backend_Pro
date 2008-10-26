<?php
	/**
	 * Setup Database Components
	 *
	 * This file contains all the component classes for the Setup Database
	 * feature. COMPONENTS ARE ONLY DEFINED HERE NOT CREATED
	 */
	
	// -------------------------------------------------------------------------

	class ConnectToDatabase extends Component
	{
		var $name = "Connect to database";
		
		function Install()
		{
			global $database;
			
			$this->status = $database->connect($_POST['database_host'],$_POST['database_name'],$_POST['database_user'],$_POST['database_password']);
			return $this->status;
		}
	}
	
	class UpdateSchema extends Component
	{
		var $name = "Update table schema";
		
		function Install()
		{
			global $database;
			
			$this->status = $database->RunSQLFile('database_schema.sql');
			return $this->status;
		}
	}
	
	class CreateAdministrator extends Component
	{
		var $name = "Create administrator user account";
		
		function Install()
		{
			global $database;
			
			// Encrypt the password
			$password = $_POST['password'] . $_POST['encryption_key'];
    		$password = sha1($password);
			
			$queries[] = "INSERT INTO `be_users` (`id` ,`username` ,`password` ,`email` ,`active` ,`group` ,`activation_key` ,`last_visit` ,`created` ,`modified`)VALUES ('1', '".$_POST['username']."', '".$password."', '".$_POST['email']."', '1', '2', NULL , NULL , NOW( ) , NULL);";
    		$queries[] = "INSERT INTO `be_user_profiles` (`user_id`) VALUES ('1')";
			
    		foreach($queries as $query)
    		{
    			if( !$database->Query($query))
    				return $this->status;
    		}
    		$this->status = TRUE;
    		return $this->status;
		}
	}
?>