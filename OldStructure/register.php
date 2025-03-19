<?php
	function error_add($message)
	{
		if(isset($_SESSION['error_register']))
			$_SESSION['error_register'] .= "<br>".$message;
		else
			$_SESSION['error_register'] = $message;
	}
	
	
	session_start();
	
	if(isset($_SESSION['user_id']))
	{
		header("Location: panel.php");
		exit();
	}
	
	if(!isset($_POST['user_email']) || !isset($_POST['g-recaptcha-response']))
	{
		header("Location: index.php");
		exit();
	}
	
	if(!isset($_POST['user_name']) || !isset($_POST['user_password']) || !isset($_POST['user_password_confirm']))
	{
		header("Location: index.php");
		exit();
	}
	
	if(!ctype_alnum($_POST['user_name']))
		error_add("Dozwolone znaki dla nazwy użytkownika: a-Z, 0-9");
	
	require_once("php-script/db_connect.php");
	
	try
	{
		if(!$db_connection = db_connect())
			throw new Exception("Bład serwera:", 1);
		
		$user_name = htmlentities($_POST['user_name'], ENT_QUOTES);
		
		if(strlen($user_name) < 3 || strlen($user_name) > 20)
			error_add("Dozwolona ilość znaków dla nazwy użytkownika: 3-20");
		
		if(!$db_query_result = $db_connection->query("SELECT user_name FROM user_data WHERE user_name = '$user_name'"))
			throw new Exception("Błąd serwera", 2);
		
		if($db_query_result->num_rows > 0)
			error_add("Wybrana nazwa użytkownika już istnieje");
		
		if(!$db_query_result = $db_connection->query("SELECT not_confirmed_name FROM not_confirmed_user_data WHERE not_confirmed_name = '$user_name'"))
			throw new Exception("Błąd serwera", 5);
		
		if($db_query_result->num_rows > 0)
			error_add("Wybrana nazwa użytkownika już istnieje");
		
		
		$user_email = $_POST['user_email'];
		
		if(!filter_var($user_email, FILTER_VALIDATE_EMAIL))
			error_add("Wprowadzono nieprawidłowy email");
		
		if(!$db_query_result = $db_connection->query("SELECT user_email FROM user_data WHERE user_email = '$user_email'"))
			throw new Exception("Bład serwera", 3);
		
		if($db_query_result->num_rows > 0)
			error_add("Podany adres email istnieje już w systemie");
		
		if(!$db_query_result = $db_connection->query("SELECT not_confirmed_email FROM not_confirmed_user_data WHERE not_confirmed_email = '$user_email'"))
			throw new Exception("Bład serwera", 6);
		
		if($db_query_result->num_rows > 0)
			error_add("Podany adres email istnieje już w systemie");
		
		
		if(strlen($_POST['user_password']) < 8 OR strlen($_POST['user_password']) > 48)
			error_add("Niepoprawna długość hasła (8-48)");
		
		if($_POST['user_password'] != $_POST['user_password_confirm'])
			error_add("Podane hasła nie są jednakowe");
		
		if(!preg_match("/[0-9]/", $_POST['user_password']) || !preg_match("/[a-z]/", $_POST['user_password']) || !preg_match("/[A-Z]/", $_POST['user_password']))
			error_add("Hasło musi zawierać jedną wielką, mała literę oraz cyfrę");
		
		if(!isset($_POST['tos']))
			error_add("Wymagana akceptacja regulaminu");
		
		if(!$_POST['g-recaptcha-response'])
			error_add("Weryfikacja reCaptcha nie powiodła się");
		
		if(isset($_SESSION['error_register']))
			throw new Exception($_SESSION['error_register']);
		
		$user_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
		
		$confirmation_key = bin2hex(random_bytes(20));
		$repeat_flag = true;
		
		while($repeat_flag)
		{
			if(!$db_result = $db_connection->query("SELECT not_confirmed_key FROM not_confirmed_user_data WHERE not_confirmed_key = '$confirmation_key'"))
				throw new Exception("Błąd serwera", 6);
			
			if($db_result->num_rows == 0)
				$repeat_flag = false;
		}
		

		
		require_once('php-script/mail_settings.php');
		
		$mail = getMail();
		
		
		$mail->addAddress($user_email);
		$mail->Subject = 'Skippit - potwierdzenie rejestracji';
		$mail->Body = '
		<html>
		<head>
			<style>
				
			</style>
		</head>
		<body>
			<h2 style="text-align: center;">Witaj '.$user_name.'.</h2>
			<p>
				Aby potwierdzić rejestrację wejdź w link poniżej:<br>
				<a href="https://skippit.pl/activate_user.php?key='.$confirmation_key.'"><b>Skippit.pl</b></a>
			</p>
		</body>
		</html>
		';
		
		
		$mail->send();
		
		if(!$db_connection->query("INSERT INTO not_confirmed_user_data (not_confirmed_name, not_confirmed_email, not_confirmed_password, not_confirmed_key) VALUES ('$user_name', '$user_email', '$user_password', '$confirmation_key')"))
			throw new Exception("Błąd serwera", 4);
		
		$_SESSION['message'] = "Rejestracja ukończona, sprawdź email w celu aktywacji konta";
	}
	catch(Exception $error)
	{
		
		echo $error->getMessage();
		$_SESSION['error_register'] = $error->getMessage();
		
		$_SESSION['error_register_return']['login'] = $_POST['user_name'];
		$_SESSION['error_register_return']['password'] = $_POST['user_password'];
		$_SESSION['error_register_return']['email'] = $_POST['user_email'];

	}
	
	db_close($db_connection);
	
	header('Location: index.php');
	
?>