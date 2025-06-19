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
		
		if(!isset($_POST['ignored_user']))
			throw new Exception('Brak dostępu', 31);
		
		if(strlen($_POST['ignored_user']) > 20 || strlen($_POST['ignored_user']) < 3)
			throw new Exception('Nie znaleziono użytkownika', 21);
		
		if(!ctype_alnum($_POST['ignored_user']))
			throw new Exception('Nie znaleziono użytkownika', 22);
		
		if(!$db_result = $db_connection->query("SELECT user_ignored FROM user_data WHERE user_id = '{$_SESSION['user_id']}'"))
			throw new Exception('Usługa niedostępna, przepraszamy.', 12);
			
		if($db_result->num_rows != 1)
			throw new Exception('Usługa niedostępna, przepraszamy.', 13);

		$db_result = $db_result->fetch_column();
		
		$ignored_users = explode(",", $db_result);
		
		foreach($ignored_users as $ignored_user_name)
		{
			if($ignored_user_name == $_POST['ignored_user'])
				throw new Exception('Użytkownik znajduje się już na czarnej liście.', 23);
		}
		
		if(!$db_result = $db_connection->query("SELECT user_id FROM user_data WHERE user_name = '{$_POST['ignored_user']}'"))
			throw new Exception('Usługa niedostępna, przepraszamy.', 14);
		
		if($db_result->num_rows == 0)
			throw new Exception('Nie znaleziono użytkownika', 24);
		
		if($db_result->num_rows > 1)
			throw new Exception('Usługa niedostępna, przepraszamy.', 15);
			
		$ignored_users = implode(",", $ignored_users);
		
		if(strlen($ignored_users) > 0)
			$ignored_users .= ',';
		
		$ignored_users .= $_POST['ignored_user'];
		
		if(!$db_connection->query("UPDATE user_data SET user_ignored = '$ignored_users' WHERE user_id = '{$_SESSION['user_id']}'"))
			throw new Exception('Usługa niedostępna, przepraszamy.', 16);
		
		$_SESSION['message'] = "Zablokowano użytkownika: ".$_POST['ignored_user'];
		
		
	}
	catch(Exception $error)
	{
		$_SESSION['error_ignore_user'] = $error->getMessage();
	}
	
	db_close($db_connection);
	
	header('Location: settings.php?user=1');
	
	

?>