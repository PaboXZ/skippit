<?php
/*
Required data: $_GET['id'];

Acces: logged in and not temporary, has access to thread
Rules: redirect to index if not logged in
redirect to panel if temporary or id is not sent or has no access
*/
?>

<?php
	
	session_start();
	
	if(!isset($_GET['id']))
	{
		$_SESSION['error_change_active_thread'] = "Access denied";
		header("Location: panel.php");
		exit();
	}
	
	require_once("php-script/rules.php");
	
	isLoggedIn();
	redirectTemporary();
	
	require_once("php-script/db_connect.php");
	
	try
	{
		
		if(!$db_connection = db_connect())
			throw new Exception("Błąd serwera", 1);
		
		$user_id = $_SESSION['user_id'];
		$thread_id = intval($_GET['id']);
		
		if(!$db_query_result = $db_connection->query("SELECT connection_thread_id FROM connection_user_thread WHERE connection_user_id = '$user_id' AND connection_thread_id = '$thread_id'"))
			throw new Exception("Błąd serwera", 2);
		
		if($db_query_result->num_rows != 1)
			throw new Exception("Błąd serwera", 3);
		
		$db_query_result->close();
		$_SESSION['user_active_thread'] = $thread_id;
		
	}
	catch(Exception $error)
	{
		$_SESSION['error_change_active_thread'] = $error->getMessage();
	}
	
	db_close($db_connection);
	
	header("Location: panel.php");
	
?>