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
		
		if(!isset($_GET['unignored_user']))
			throw new Exception('Brak dostępu.', 31);
		
		if(!$db_result = $db_connection->query("SELECT user_ignored FROM user_data WHERE user_id = '{$_SESSION['user_id']}'"))
			throw new Exception('Usługa niedostępna, przepraszamy.', 12);
		
		if($db_result->num_rows != 1)
			throw new Exception('Usługa niedostępna, przepraszamy.', 13);
		
		$db_result = $db_result->fetch_column();
		
		if(strlen($db_result) > 0)
		{
			$db_result = explode(',', $db_result);
			
			for($i = 0; $i < count($db_result); $i++)
			{
				if($db_result[$i] == $_GET['unignored_user'])
				{
					$unignored_user_key = $i;
					break;
				}
			}
			
			if(isset($unignored_user_key))
			{
				$first_part = array_slice($db_result, 0, $i);
				$second_part = array_slice($db_result, $i+1);
				
				$db_result = implode('.', array_merge($first_part, $second_part));
				
				if(!$db_connection->query("UPDATE user_data SET user_ignored = '$db_result' WHERE user_id = '{$_SESSION['user_id']}'"))
					throw new Exception('Usługa niedostępna, przepraszamy.', 13);
				
			}
			else
				throw new Exception('Nie znaleziono użytkownika', 21);
		}
		else
			throw new Exception('Brak dostępu.', 32);
		
		$_SESSION['message'] = 'Odblokowano powiadomienia od: '.$_GET['unignored_user'];
		
	}
	catch(Exception $error)
	{
		$_SESSION['error_unignore_user'] = $error->getMessage();
	}
	
	db_close($db_connection);

	header('Location: settings.php?user=1');

?>