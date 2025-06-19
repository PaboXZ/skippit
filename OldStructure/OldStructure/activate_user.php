<?php

	session_start();
	
	if(isset($_SESSION['user_id']))
	{
		header('panel.php');
		exit();
	}
		
	if(!isset($_GET['key']))
	{
		header('index.php');
		exit();
	}
	
	if(!ctype_alnum($_GET['key']))
	{
		exit('Access Denied');
	}
	
	require_once('php-script/db_connect.php');
	
	session_start();
	
	try
	{
		
		if(!$db_connection = db_connect())
			throw new Exception("Bład serwera", 21);
		
		if(!$db_result = $db_connection->query("SELECT not_confirmed_email, not_confirmed_password, not_confirmed_name FROM not_confirmed_user_data WHERE not_confirmed_key = '{$_GET['key']}'"))
			throw new Exception("Bład serwera", 22);
		
		if($db_result->num_rows != 1)
			throw new Exception('Nie znaleziono użytkownika', 11);
		
		$db_result = $db_result->fetch_assoc();
		
		if(!$db_connection->query("INSERT INTO user_data (user_name, user_password, user_email, user_fav_threads, user_ignored) VALUES ('{$db_result['not_confirmed_name']}', '{$db_result['not_confirmed_password']}', '{$db_result['not_confirmed_email']}', '0 0 0 0 0', '')"))
			throw new Exception('Błąd serwera', 23);
		
		if(!$db_connection->query("DELETE FROM not_confirmed_user_data WHERE not_confirmed_key = '{$_GET['key']}'"))
			throw new Exception("Bład serwera", 24);
		
		$_SESSION['message'] = "Aktywacja konta ukończona, możesz się zalogować";
		
	}
	catch(Exception $error)
	{
		$_SESSION['error_activate_user'] = $error->getMessage();
	}

	db_close($db_connection);
	header('Location: index.php');

?>