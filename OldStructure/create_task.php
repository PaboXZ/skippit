<?php
/*
Access: logged in users with active thread
Required data: task_title, task_content, task_power

Rules:
If not logged in go to index
If no active thread go to panel

If no data was sent (after login check) go to panel
Then check values with database requirements

Rights!!  power level
*/
	
?>

<?php

	function errorAdd($message)
	{
		if(isset($_SESSION['error_create_task']))
		{
			$_SESSION['error_create_task'] .= "<br>".$message;
		}
		else
		{
			$_SESSION['error_create_task'] = $message;
		}
	}

	session_start();
	
	require_once('php-script/rules.php');
	isLoggedIn();
	
	if(!isset($_POST['task_title']) || !isset($_POST['task_content']) || !isset($_POST['task_power']))
	{
		header('Location: panel.php');
		exit();
	}
	
	$task_title = htmlentities($_POST['task_title'], ENT_QUOTES);
	$task_content = htmlentities($_POST['task_content'], ENT_QUOTES);
	$task_power = intval($_POST['task_power']);
	
	if(strlen($task_title) < 3 || strlen($task_title) > 64)
		errorAdd("Dozwolona długość nazwy wpisu: 3-60");
	
	if(strlen($task_content) > 2024)
		errorAdd("Maksymalna długość wpisu: 1900");
	
	if($task_power > 5 OR $task_power < 1)
		errorAdd("Nieznany błąd");
	
	require_once("php-script/db_connect.php");
	
	try
	{
		if(!$db_connection = db_connect())
			throw new Exception("Błąd serwera", 1);
		
		$user_id = $_SESSION['user_id'];
		$task_thread_id = $_SESSION['user_active_thread'];


		if(!$db_query_result = $db_connection->query("SELECT task_id FROM task_data WHERE task_thread_id = '$task_thread_id'"))
			throw new Exception("Bład serwera", 2);
		
		if($db_query_result->num_rows >= 1024)
			throw new Exception("Przekroczono dozwoloną ilość wpisów (1024)", 21);
		
		if(!$db_query_result = $db_connection->query("SELECT thread_id FROM thread_data WHERE thread_id = '$task_thread_id'"))
			throw new Exception("Bład serwera", 3);
		
		if($db_query_result->num_rows != 1)
			throw new Exception("Bład serwera", 4);
		
		
		if(!$db_query_result = $db_connection->query("SELECT task_id FROM task_data WHERE task_user_id = '$user_id' AND task_thread_id = '$task_thread_id' AND task_title = '$task_title'"))
			throw new Exception("Bład serwera", 5);
		
		if($db_query_result->num_rows > 0)
			throw new Exception("Podana nazwa wpisu już istnieje");
		
		if(!$db_query_result = $db_connection->query("SELECT connection_create_power FROM connection_user_thread WHERE connection_user_id = '$user_id' AND connection_thread_id = '$task_thread_id'"))
			throw new Exception("Bład serwera", 6);
		
		$db_result_row = $db_query_result->fetch_assoc();
		
		if($task_power < $connection_create_power)
			errorAdd("Przekroczono dozwoloną siłę wpisu");
		
		if(isset($_SESSION['error_create_task']))
			throw new Exception($_SESSION['error_create_task'], 22);
		
		if(!$db_connection->query("INSERT INTO task_data (task_thread_id, task_user_id, task_title, task_content, task_power) VALUES ('$task_thread_id', '$user_id', '$task_title', '$task_content', '$task_power')"))
			throw new Exception("Błąd serwera", 7);
	}
	catch(Exception $error)
	{
		$_SESSION['error_create_task'] = $error->getMessage();
		$_SESSION['create_task_return_name'] = $_POST['task_title'];
		$_SESSION['create_task_return_content'] = $_POST['task_content'];
	}
	
	db_close($db_connection);
	header('Location: panel.php');

?>