<?php
	session_start();
	
	require_once('php-script/rules.php');
	
	isLoggedIn();
	redirectTemporary();
	
	require_once('php-script/db_connect.php');
	
	try
	{
		
		if(!$db_connection = db_connect())
			throw new Exception("Usługa niedostępna, za utrudnienia przepraszamy.", 11);
		
		if(!isset($_POST['user_password']) || !isset($_POST['user_new_password']) || !isset($_POST['user_confirm_password']))
			throw new Exception("Brak dostępu.", 01);
		
		if(!$db_result = $db_connection->query("SELECT user_password FROM user_data WHERE user_id = '{$_SESSION['user_id']}'"))
			throw new Exception("Usługa niedostępna, za utrudnienia przepraszamy.", 12);
		
		if($db_result->num_rows != 1)
			throw new Exception("Usługa niedostępna, za utrudnienia przepraszamy.", 13);
		
		$db_result = $db_result->fetch_assoc();
		
		if(!password_verify($_POST['user_password'], $db_result['user_password']))
			throw new Exception("Podano błędne hasło.", 21);
		
		if(strlen($_POST['user_new_password']) > 48 || strlen($_POST['user_new_password']) < 8)
			throw new Exception("Niepoprawna długość hasła (8-48)", 22);
		
		if(!preg_match("/[0-9]/", $_POST['user_new_password']) || !preg_match("/[a-z]/", $_POST['user_new_password']) || !preg_match("/[A-Z]/", $_POST['user_new_password']))
			throw new Exception("Hasło musi zawierać jedną wielką, małą literę oraz cyfrę", 23);
		
		if($_POST['user_new_password'] != $_POST['user_confirm_password'])
			throw new Exception("Nowe hasło i potwierdzenie hasła muszą być identyczne.", 24);
		
		$new_password = password_hash($_POST['user_new_password'], PASSWORD_DEFAULT);
		
		if(!$db_connection->query("UPDATE user_data SET user_password = '$new_password' WHERE user_id = '{$_SESSION['user_id']}'"))
			throw new Exception("Usługa niedostępna, za utrudnienia przepraszamy.", 14);
		
		$_SESSION['message'] = "Zmieniono hasło.";
	}
	catch(Exception $error)
	{
		$_SESSION['error_change_user_password'] = $error->getMessage();
	}
	
	db_close($db_connection);

	header('Location: settings.php?user=1#zmien-haslo');



?>