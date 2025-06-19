<?php

	session_start();
	
	require_once('php-script/rules.php');
	
	isLoggedIn();
	redirectTemporary();
	
	require_once('php-script/db_connect.php');
	
	try
	{
		if(!$db_connection = db_connect())
			throw new Exception('Usługa niedostępna, przepraszamy.', 11);
		
		if(!isset($_POST['user_password_delete']) || !isset($_POST['user_password_delete_confirm']))
			throw new Exception('Brak dostępu.', 31);
		
		if($_POST['user_password_delete'] != $_POST['user_password_delete_confirm'])
			throw new Exception('Hasła nie są jednakowe.', 21);
		
		if(!$db_result = $db_connection->query("SELECT user_password FROM user_data WHERE user_id = {$_SESSION['user_id']}"))
			throw new Exception('Usługa niedostępna, przepraszamy.', 12);
		
		if($db_result->num_rows != 1)
			throw new Exception('Usługa niedostępna, przepraszamy.', 13);
		
		$db_result = $db_result->fetch_column();
		
		if(!password_verify($_POST['user_password_delete'], $db_result))
			throw new Exception('Nieprawidłowe hasło.', 32);
		
		if(!$db_connection->query("DELETE FROM user_data WHERE user_id = '{$_SESSION['user_id']}'"))
			throw new Exception('Usługa niedostępna, przepraszamy.', 14);
		
		if(!$db_result = $db_connection->query("SELECT thread_id FROM thread_data WHERE thread_owner_id = '{$_SESSION['user_id']}'"))
			throw new Exception('', 41);
		
		$user_id = $_SESSION['user_id'];
		
		session_destroy();
		
		for($i = $db_result->num_rows; $i > 0; $i--)
		{
			$thread_id = $db_result->fetch_column();
			
			if(!$db_connection->query("DELETE FROM task_data WHERE task_thread_id = '$thread_id'"))
				throw new Exception('',42);
			
			if(!$db_connection->query("DELETE FROM connection_user_thread WHERE connection_thread_id = '$thread_id'"))
				throw new Exception('',43);
			
			if(!$db_connection->query("DELETE FROM thread_data WHERE thread_id = '$thread_id'"))
				throw new Exception('',44);
			
			if(!$db_connection->query("DELETE FROM user_data WHERE user_email = '$thread_id'"))
				throw new Exception('',45);
		}
		
		unset($db_result);
		
		if(!$db_connection->query("DELETE FROM connection_user_thread WHERE connection_user_id = '$user_id'"))
			throw new Exception('', 46);
		
	}
	catch(Exception $error)
	{
		if($error->getCode() < 40)
			$_SESSION['error_delete_user'] = $error->getMessage();
		echo $error->getMessage();
	}
	
	db_close($db_connection);
	
	header('Location: index.php');
?>