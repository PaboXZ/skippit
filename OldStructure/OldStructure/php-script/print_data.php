<?php
	function printThreadNames($db_connection, $user_id)
	{
		try
		{
			if(!$db_query_result = $db_connection->query("SELECT thread_name FROM thread_data INNER JOIN connection_user_thread ON connection_thread_id = thread_id WHERE connection_user_id = '$user_id' ORDER BY connection_thread_id DESC"))
			{
				throw new Exception("Błąd połączenia", 1);
			}
			$returnArray = FALSE;
			$db_result = $db_query_result->fetch_all();
			for($i = $db_query_result->num_rows; $i > 0; $i--)
			{
				$returnArray[$i] = $db_result[$i-1][0];
			}
			return $returnArray;
			$db_query_result->close();
		}
		catch(Exception $error)
		{
			return FALSE;
		}
	}
	
	
	function printThreadNamesId($db_connection, $user_id)
	{
		try
		{
			if(!$db_query_result = $db_connection->query("SELECT thread_id, thread_name FROM thread_data INNER JOIN connection_user_thread ON connection_thread_id = thread_id WHERE connection_user_id = '$user_id' ORDER BY connection_thread_id DESC"))
			{
				throw new Exception("Błąd połączenia", 1);
			}
			$returnArray = FALSE;
			$db_result = $db_query_result->fetch_all();
			for($i = $db_query_result->num_rows; $i > 0; $i--)
			{
				$returnArray[$i][1] = $db_result[$i-1][1];
				$returnArray[$i][0] = $db_result[$i-1][0];
			}
			return $returnArray;
			$db_query_result->close();
		}
		catch(Exception $error)
		{
			return FALSE;
		}
	}

	function printArrayHTML($array, $htmlbefore, $htmlafter)
	{
		$returnVal = "";
		foreach($array as $value)
		{
			$returnVal .= $htmlbefore.$value.$htmlafter;
		}
		return $returnVal;
	}
	
	function favoriteThreadsSet($db_connection, $user_id, $threads_array)
	{
		$string = implode(" ", $threads_array);
		
		try
		{
			if(!$db_connection->query("UPDATE user_data SET user_fav_threads = '$string' WHERE user_id = '$user_id'"))
			{
				throw new Exception();
			}
			return true;
		}
		catch(Exception $error)
		{
			return false;
		}
	}
	
	function favoriteThreadsGet($db_connection, $user_id)
	{
		try
		{
			if(!$db_result_row = $db_connection->query("SELECT user_fav_threads FROM user_data WHERE user_id = '$user_id'"))
			{
				throw new Exception();
			}
			$db_result = $db_result_row->fetch_assoc()['user_fav_threads'];
			return explode(" ", $db_result);	
		}
		catch(Exception $error)
		{
			return false;
		}
	}

?>