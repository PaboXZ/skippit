<?php
	session_start();
	
	require_once('php-script/rules.php');
	isLoggedIn();
	redirectTemporary();
	
	require_once('php-script/check_thread.php');
	
	if(checkThreadOwner($_SESSION['user_active_thread']) != $_SESSION['user_id'])
	{
		exit("Access denied");
	}
	
	if(!isset($_POST['user_name']))
	{
		exit("Access denied");
	}
	
	require_once('php-script/db_connect.php');
	
	$thread_id = $_SESSION['user_active_thread'];
	try
	{		
		if(!$db_connection = db_connect())
			throw new Exception();
		
		$user_name = $_POST['user_name'];
		
		if(!ctype_alnum($user_name))
			throw new Exception("Dozwolone znaki dla loginu: sa-Z 0-9", 10);

		
		if(isset($_POST['user_password']))
		{
			$user_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
			
			if(!$db_result = $db_connection->query("SELECT user_id FROM user_data WHERE user_name = '$user_name'"))
				throw new Exception("Błąd serwera", 0);
			
			if($db_result->num_rows > 0)
				throw new Exception("Wybrana nazwa użytkownika już istnieje", 11);
			
			if(!$db_connection->query("INSERT INTO user_data (user_email, user_password, user_name) VALUES ('$thread_id', '$user_password', '$user_name')"))
				throw new Exception("Błąd serwera", 1);
			
			if(!$db_result = $db_connection->query("SELECT user_id FROM user_data WHERE user_name = '$user_name'"))
				throw new Exception("Bład serwera", 2);
			
			$db_result = $db_result->fetch_assoc();
			
			$new_user_id = $db_result['user_id'];
			
			if(!$db_connection->query("INSERT INTO connection_user_thread (connection_user_id, connection_thread_id) VALUES ('$new_user_id', '$thread_id')"))
				throw new Exception("Błąd serwera", 3);
			
		}
		else
			throw new Exception("Access Denied");
		
	}
	catch(Exception $error)
	{
		echo $error->getMessage();
	}
	
	db_close($db_connection);
	
	header('Location: settings.php');
?>