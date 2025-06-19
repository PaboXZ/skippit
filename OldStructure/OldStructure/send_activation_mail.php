<?php

	session_start();
		
	if(isset($_SESSION['user_id']))
	{
		header('Location: panel.php');
		exit();
	}
	if(!isset($_GET['user_name']))
	{
		exit('Access Denied');
	}


	require_once('php-script/db_connect.php');
	
	try
	{
		if(!$db_connection = db_connect())
			throw new Exception('Błąd serwera', 21);
		
		if(!ctype_alnum($_GET['user_name']))
			throw new Exception("Access Denied");
		
		if(!$db_result = $db_connection->query("SELECT not_confirmed_key, not_confirmed_email, not_confirmed_timestamp FROM not_confirmed_user_data WHERE not_confirmed_name = '{$_GET['user_name']}'"))
			throw new Exception('Bład serwera', 22);
		
		if($db_result->num_rows != 1)
			throw new Exception('Wystąpił błąd.', 23);
		
		$db_result = $db_result->fetch_assoc();
		
		if(substr($db_result['not_confirmed_timestamp'], 0, -6) == date('Y-m-d H'))
		{
			if(intval(date('i')) <= intval(substr($db_result['not_confirmed_timestamp'], -5, 2)) + 5)
				throw new Exception("Zbyt wiele zapytań, sprawdź skrzynkę email lub spróbuj ponownie później.", 11);
			else
			{
				if(!$db_connection->query("UPDATE not_confirmed_user_data SET not_confirmed_timestamp = CURRENT_TIMESTAMP WHERE not_confirmed_name = '{$_GET['user_name']}'"))\
					throw new Exception("Błąd serwera", 24);
			}
		}
		else
		{
			if(!$db_connection->query("UPDATE not_confirmed_user_data SET not_confirmed_timestamp = CURRENT_TIMESTAMP WHERE not_confirmed_name = '{$_GET['user_name']}'"))
				throw new Exception("Błąd serwera", 24);
		}
		
		require_once('php-script/mail_settings.php');
		
		$mail = getMail();
		
		$mail->addAddress($db_result['not_confirmed_email']);
		$mail->Subject = 'Skippit - potwierdzenie rejestracji';
		$mail->Body = '
		<html>
		<head>
			<style>
				
			</style>
		</head>
		<body>
			<h2 style="text-align: center;">Witaj '.$_GET['user_name'].'.</h2>
			<p>
				Aby potwierdzić rejestrację wejdź w link poniżej:<br>
				<a href="https://skippit.pl/activate_user.php?key='.$db_result['not_confirmed_key'].'"><b>Skippit.pl</b></a>
			</p>
		</body>
		</html>
		';
		
		
		$mail->send();
		
		$_SESSION['message'] = "Wysłano link aktywacyjny na podany adres email";
		
	}
	catch(Exception $error)
	{
		$_SESSION['error_send_activation_mail'] = $error->getMessage();
	}

	db_close($db_connection);
	
	header('Location: index.php');

?>