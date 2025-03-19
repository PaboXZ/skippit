<?php
	if(!isset($_POST['thread_version']) || !isset($_POST['thread_name']))
	{
		header("Location: panel.php");
		exit();
	}
	
	session_start();
	
	require_once("php-script/rules.php");
	require_once("php-script/db_connect.php");
	require_once("php-script/print_data.php");
	
	isLoggedIn();
	redirectTemporary();
	
	$thread_owner_id = $_SESSION['user_id'];
	$thread_name = htmlentities($_POST['thread_name'], ENT_QUOTES);
	$thread_version = htmlentities($_POST['thread_version'], ENT_QUOTES);
	
	$db_thread_version = 0;
	
	switch ($thread_version)
	{
		case "pro":
			$db_thread_version = 1;
	}
	
	try
	{
		if(strlen($thread_name) > 24 OR strlen($thread_name) < 3)
			throw new Exception("Niewłaściwa długość nazwy (3 - 24)", 21);
		
		if(!$db_connection = db_connect())
			throw new Exception("Błąd serwera", 0);
		
		if(!$db_query_result = $db_connection->query("SELECT thread_id FROM thread_data WHERE thread_owner_id = '$thread_owner_id'"))
			throw new Exception("Błąd serwera", 1);
		
		if($db_query_result->num_rows > 10)
			throw new Exception("Osiągnięto maksymanlną ilość list: 10", 10);
		
		if(!$db_query_result = $db_connection->query("SELECT thread_id FROM thread_data WHERE thread_owner_id = '$thread_owner_id' AND thread_name = '$thread_name'"))
			throw new Exception("Bład serwera", 2);
		
		if($db_query_result->num_rows > 0)
			throw new Exception("Wybrana nazwa już istnieje.");
		
		if(!$db_connection->query("INSERT INTO thread_data (thread_owner_id, thread_name, thread_version) VALUES ('$thread_owner_id', '$thread_name', '$db_thread_version')"))
			throw new Exception("Błąd serwera", 3);
		
		if(!$db_query_result = $db_connection->query("SELECT thread_id FROM thread_data WHERE thread_owner_id = '$thread_owner_id' AND thread_name = '$thread_name'"))
			throw new Exception("Błąd serwera", 4);
		
		
		$thread_id = $db_query_result->fetch_assoc()['thread_id'];
		
		$favorite_threads = favoriteThreadsGet($db_connection, $_SESSION['user_id']);
		
		for($i = 0; $i < 5; $i++)
		{
			if($favorite_threads[$i] == '0')
			{
				$favorite_threads[$i] = $thread_id;
				favoriteThreadsSet($db_connection, $_SESSION['user_id'], $favorite_threads);
				$changed_flag = true;
				break;
			}
		}
		
		if(!isset($changed_flag))
		{
			$_SESSION['message'] = "Utworzono listę: ".$thread_name.". Jest dostępna z poziomu ustawień.";
		}
		
		if(!$db_connection->query("INSERT INTO connection_user_thread (connection_user_id, connection_thread_id, connection_view_power, connection_is_owner, connection_edit_permission, connection_delete_permission, connection_create_power, connection_complete_permission) VALUES ('$thread_owner_id', '$thread_id', '15', '1', '1', '1', '15', '1')"))
			throw new Exception("Błąd serwera", 5);
			
		$_SESSION['user_active_thread'] = $thread_id;
	}
	catch(Exception $error)
	{
		if($error->getCode() == 3 OR $error->getCode() == 5)
		{
			$db_connection->query("DELETE FROM thread_data WHERE thread_owner_id = '$thread_owner_id' AND thread_name = '$thread_name'");
		}
		
		$_SESSION['error_create_thread'] = $error->getMessage();
		$_SESSION['create_thread_return_name'] = $_POST['thread_name'];
	}
	
	db_close($db_connection);
	header("Location: panel.php");
?>