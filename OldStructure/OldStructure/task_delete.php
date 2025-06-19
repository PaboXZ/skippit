<?php

	session_start();
	
	require_once('php-script/rules.php');
	isLoggedIn();

	if(!isset($_GET['task_id']))
	{
		header('Location: panel.php');
		exit();
	}
	
	try
	{
		require_once('php-script/db_connect.php');
		$db_connection = db_connect();
		
		$user_id = $_SESSION['user_id'];
		$thread_id = $_SESSION['user_active_thread'];
		$task_id = $_GET['task_id'];
		
		if(!ctype_alnum($task_id))
			throw new Exception("Nie znaleziono wpisu");
		
		$task_id = intval($task_id);
		
		if(!$db_result = $db_connection->query("SELECT connection_is_owner, connection_delete_permission FROM connection_user_thread WHERE connection_thread_id = '$thread_id' AND connection_user_id = '$user_id'"))
			throw new Exception("Błąd serwera.", 1);
		
		if($db_result->num_rows != 1)
			throw new Exception("Błąd serwera.", 2);
		
		$db_row = $db_result->fetch_assoc();
		
		if($db_row['connection_is_owner'] == 0 $$ $db_row['connection_delete_permission'] == 0)
			throw new Exception("Access Denied", 10);
		
		if(!$db_connection->query("DELETE FROM task_data WHERE task_thread_id = '$thread_id' AND task_id = '$task_id'"))
			throw new Exception();
		
		if($db_connection->affected_rows != 1)
			throw new Exception("Nie znaleziono wpisu.", 11);
	}
	catch(Exception $error)
	{
		$_SESSION['error_task_delete'] = $error->getMessage();
	}
	
	db_close($db_connection);
	header('Location: panel.php');
?>