<?php

	session_start();
	
	require_once('php-script/rules.php');
	
	isLoggedIn();
	redirectTemporary();
	
	require_once('php-script/db_connect.php');
	
	try
	{
		if(!$db_connection = db_connect())
			throw new Exception();
		
		if(!isset($_GET['notification_id']) OR !ctype_digit($_GET['notification_id']))
			throw new Exception();
		
		if(!$db_result = $db_connection->query("SELECT notification_user_to FROM notification_data WHERE notification_id = '{$_GET['notification_id']}'"))
			throw new Exception();
		
		if($db_result->num_rows != 1)
			throw new Exception();
		
		$db_result = $db_result->fetch_column();
		
		if($db_result != $_SESSION['user_id'])
			throw new Exception();
		
		if(!$db_connection->query("DELETE FROM notification_data WHERE notification_id = '{$_GET['notification_id']}'"))
			throw new Exception();
	}
	catch(Exception $error)
	{
	}

	db_close($db_connection);

	header('Location: panel.php');
?>