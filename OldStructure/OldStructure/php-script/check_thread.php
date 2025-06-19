<?php
	if(count(get_included_files()) == 1)
	{
		exit("Access denied.");
	}
	
	
	function checkThreadById($user_id, $thread_id)
	{
		require_once('php-script/db_connect.php');
	
		try
		{
			if(!ctype_alnum($user_id))
			{
				throw new Exception("Access denied", 0);
			}
			if(!$db_connection = db_connect())
			{
				throw new Exception("Błąd połaczenia", 1);
			}
			if(!$db_result = $db_connection->query("SELECT connection_thread_id FROM connection_user_thread WHERE connection_user_id = '$user_id' AND connection_thread_id = '$thread_id'"))
			{
				throw new Exception("Błąd połączenia", 2);
			}
			if($db_result->num_rows != 1)
			{
				throw new Exception("Brak praw do listy", 3);
			}
			db_close($db_connection);
			return true;
		}
		catch(Exception $error)
		{
			db_close($db_connection);
			return false;
		}
	}
	
	function checkThreadByName($user_id, $thread_name)
	{
		require_once('php-script/db_connect.php');
		$thread_name = htmlentities($thread_name);
		try
		{
			if(!$db_connection = db_connect())
			{
				throw new Exception("Błąd połaczenia", 1);
			}
			if(!$db_result = $db_connection->query("SELECT thread_id FROM thread_data INNER JOIN connection_user_thread ON thread_id = connection_user_thread WHERE thread_name = '$thread_name' AND connection_user_id = '$user_id'"))
			{
				throw new Exception("Błąd połączenia", 2);
			}
			if($db_result->num_rows != 1)
			{
				throw new Exception("Brak praw do listy", 3);
			}
			db_close($db_connection);
			return true;
		}
		catch(Exception $error)
		{
			db_close($db_connection);
			return false;
		}
	}
	
	function checkThreadOwner($thread_id)
	{
		try
		{
			require_once('php-script/db_connect.php');
			if(!$db_connection = db_connect())
			{
				throw new Exception();
			}
			if(!$db_result = $db_connection->query("SELECT thread_owner_id FROM thread_data WHERE thread_id = '$thread_id'"))
			{
				throw new Exception();
			}
			if($db_result->num_rows != 1)
			{
				throw new Exception();
			}
			$db_result = $db_result->fetch_assoc();
			
			db_close($db_connection);
			return $db_result['thread_owner_id'];
		}
		catch(Exception $error)
		{
			db_close($db_connection);
			return false;
		}
	}

?>