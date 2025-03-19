<?php
	if(count(get_included_files()) == 1)
	{
		exit("Access denied.");
	}
	
	function db_connect(){
		
		require('db_credentials.php');
		
		try
		{
			$db_connection = new mysqli($db_host, $db_user, $db_password, $db_name);
			return $db_connection;
		}
		catch(Exception $error)
		{
			return false;
		}
	}
	
	function db_close($db_connection)
	{
		if(isset($db_connection->host_info))
		{
			$db_connection->close();
			return true;
		}
		else return false;
	}
	
?>