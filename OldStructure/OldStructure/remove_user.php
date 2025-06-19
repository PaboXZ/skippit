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
		
		if(!isset($_GET['user_id']))
			throw new Exception();
		
		if(!ctype_digit($_GET['user_id']))
			throw new Exception();
		
		if(!$db_result = $db_connection->query("SELECT connection_is_owner FROM connection_user_thread WHERE connection_user_id = '{$_SESSION['user_id']}' AND connection_thread_id = '{$_SESSION['user_active_thread']}'"))
			throw new Exception();
		
		if($db_result->num_rows != 1)
			throw new Exception();
		
		$db_result = $db_result->fetch_column();
		
		if(!$db_result)
			throw new Exception();
		
		if(!$db_result = $db_connection->query("SELECT connection_id FROM connection_user_thread WHERE connection_thread_id = '{$_SESSION['user_active_thread']}' AND connection_user_id = '{$_GET['user_id']}'"))
			throw new Exception();
		
		if($db_result->num_rows != 1)
			throw new Exception();
		
		$db_result = $db_result->fetch_column();
		
		if(!$db_connection->query("DELETE FROM connection_user_thread WHERE connection_id = '$db_result'"))
			throw new Exception();
	}
	catch(Exception $error)
	{
	}
	
	db_close($db_connection);
	
	header('Location: settings.php?thread_id='.$_SESSION['user_active_thread']);



?>