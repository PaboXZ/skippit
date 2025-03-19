<?php
	
	session_start();
	
	require_once('php-script/rules.php');
	
	isLoggedIn();
	redirectTemporary();
	
	require_once('php-script/db_connect.php');
	
	try
	{
		if(!isset($_POST['user_name']))
			throw new Exception('Access Denied', 22);
		
		if(!$db_connection = db_connect())
			throw new Exception('Usługa niedostępna, przepraszamy.', 11);
		
		if(!$db_result = $db_connection->query("SELECT connection_is_owner FROM connection_user_thread WHERE connection_user_id = '{$_SESSION['user_id']}' AND connection_thread_id = '{$_SESSION['user_active_thread']}'"))
			throw new Exception('Usługa niedostępna, przepraszamy.', 12);
		
		if($db_result->num_rows != 1)
			throw new Exception('Access Denied', 21);
		
		$db_result = $db_result->fetch_column();
		
		if(!$db_result)
			throw new Exception('Access Denied');
		
		require_once('php-script/notification_print.php');
		
		if(filter_var($_POST['user_name'], FILTER_VALIDATE_EMAIL))
		{
			if(!$db_result = $db_connection->query("SELECT user_id FROM user_data WHERE user_email = '{$_POST['user_name']}'"))
				throw new Exception('Usługa niedostępna, przepraszamy.', 13);
			
			if($db_result->num_rows != 1)
				throw new Exception('Nie znaleziono użytkownika', 31);
		}
		else
		{
			if(!ctype_alnum($_POST['user_name']))
				throw new Exception("Nie znaleziono użytkownika", 34);
			
			if(!$db_result = $db_connection->query("SELECT user_id FROM user_data WHERE user_name = '{$_POST['user_name']}'"))
				throw new Exception('Usługa niedostępna, przepraszamy.', 14);
			
			if($db_result->num_rows != 1)
				throw new Exception('Nie znaleziono użytkownika', 32);
		}	
		
		$user_id = $db_result->fetch_column();
		
		if(!$db_result = $db_connection->query("SELECT connection_thread_id FROM connection_user_thread WHERE connection_thread_id = '{$_SESSION['user_active_thread']}' AND connection_user_id = '$user_id'"))
			throw new Exception('Usługa niedostępna, przepraszamy.', 16);
		
		if($db_result->num_rows > 0)
			throw new Exception('Użytkownik ma już dostęp do listy', 34);
		
		if(!$db_result = $db_connection->query("DELETE FROM notification_data WHERE notification_thread_id = '{$_SESSION['user_active_thread']}'"))
			throw new Exception('Usługa niedostępna, przepraszamy.', 17);
		
		if(!$db_result = $db_connection->query("SELECT thread_name FROM thread_data WHERE thread_id =  '{$_SESSION['user_active_thread']}'"))
			throw new Exception('Usługa niedostępna, przepraszamy.', 18);
		
		if($db_result->num_rows != 1)
			throw new Exception('Usługa niedostępna, przepraszamy.', 19);
		
		$thread_name = $db_result->fetch_column();
		
		$status = notificationWrite($user_id, $_SESSION['user_id'], "", $_SESSION['user_active_thread']);
		
		if(!$db_result = $db_connection->query("SELECT notification_id FROM notification_data WHERE notification_thread_id = '{$_SESSION['user_active_thread']}'"))
			throw new Exception('Usługa niedostępna, przepraszamy.', 110);
		
		$notification_id = $db_result->fetch_column();
		
		if(!$db_result = $db_connection->query("UPDATE notification_data SET notification_content = 'B,Użytkownik {$_SESSION['user_name']} zaprosił Cię do listy: $thread_name,A,accept_invitation.php?id=$notification_id,Akceptuj,C'WHERE notification_thread_id = '{$_SESSION['user_active_thread']}'"))
			throw new Exception('Usługa niedostępna, przepraszamy.', 110);
		
		if($status == 23)
			throw new Exception('Zablokowany przez użytkownika', 33);
		
		if($status != 0)
			throw new Exception('Usługa niedostępna, przepraszamy.', 15);
		
		$_SESSION['message'] = 'Wysłano zaproszenie do listy.';
			
	}
	catch(Exception $error)
	{
		$_SESSION['error_add_user'] = $error->getMessage();
	}

	header("Location: settings.php?thread_id={$_SESSION['user_active_thread']}");


?>