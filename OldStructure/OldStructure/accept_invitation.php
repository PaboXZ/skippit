<?php

	session_start();
	
	require_once('php-script/rules.php');
	
	isLoggedIn();
	redirectTemporary();
	
	require_once('php-script/db_connect.php');
	
	try
	{
		if(!isset($_GET['id']))
			throw new Exception();
		
		if(!$db_connection = db_connect())
			throw new Exception();
		
		if(!ctype_digit($_GET['id']))
			throw new Exception();
		
		if(!$db_result = $db_connection->query("SELECT notification_thread_id FROM notification_data WHERE notification_id = '{$_GET['id']}' AND notification_user_to = '{$_SESSION['user_id']}'"))
			throw new Exception();
		
		if($db_result->num_rows != 1)
			throw new Exception();
		
		$db_row = $db_result->fetch_assoc();
		
		if(!$db_result = $db_connection->query("SELECT connection_id FROM connection_user_thread WHERE connection_thread_id = '{$db_row['notification_thread_id']}' AND connection_user_id = '{$_SESSION['user_id']}'"))
			throw new Exception();
		
		if($db_result->num_rows > 0)
			throw new Exception();
		
		if(!$db_connection->query("DELETE FROM notification_data WHERE notification_id = '{$_GET['id']}'"))
			throw new Exception();
		
		if(!$db_connection->query("INSERT INTO connection_user_thread (connection_thread_id, connection_user_id, connection_create_power) VALUES ('{$db_row['notification_thread_id']}', '{$_SESSION['user_id']}', '5')"))
			throw new Exception();
		
		$_SESSION['user_active_thread'] = $db_row['notification_thread_id'];
		
		
	}
	catch(Exception $error)
	{
	}

	header('Location: panel.php');




?>