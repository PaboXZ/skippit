<?php
	
	session_start();
	
	require_once('php-script/rules.php');
	
	isLoggedIn();
	redirectTemporary();

	require_once('php-script/db_connect.php');

	try
	{

		if(!$db_connection = db_connect())
			throw new Exception("Usługa niedostępna, przepraszamy.", 11);
		
		if(!$db_result = $db_connection->query("SELECT user_notifications_ignore FROM user_data WHERE user_id = '{$_SESSION['user_id']}'"))
			throw new Exception("Usługa niedostępna, przepraszamy.", 12);
			
		$db_result = $db_result->fetch_column();
		
		if($db_result == 0)
			$db_result = 1;
		else
			$db_result = 0;
		
		if(!$db_connection->query("UPDATE user_data SET user_notifications_ignore = '$db_result' WHERE user_id = '{$_SESSION['user_id']}'"))
			throw new Exception("Usługa niedostępna, przepraszamy.", 13);
		
		$_SESSION['user_notifications_ignore'] = $db_result;
		
		if($db_result)
			$_SESSION['message'] = "Zablokowano powiadomienia";
		else
			$_SESSION['message'] = "Odblokowano powiadomienia";
			
	}
	catch(Exception $error)
	{
		$_SESSION['error_ignore_notifications'] = $error->getMessage();
	}

	db_close($db_connection);
	header('Location: settings.php?user=1');

?>