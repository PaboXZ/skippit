<?php
	
	session_start();
	require_once('php-script/rules.php');
	
	isLoggedIn();
	redirectTemporary();
	
	if(!isset($_POST['favorite-1']))
	{
		header('Location: panel.php');
		exit();
	}

	require_once('php-script/db_connect.php');
	require_once('php-script/print_data.php');
	
	try
	{
		if(!$db_connection = db_connect())
			throw new Exception("Bład serwera", 0);
		
		$i = 1;
		$next_free_index = 0;
		$repeat_flag = false;
		
		$favorite_threads_array = array("0", "0", "0", "0", "0");
		
		while(isset($_POST['favorite-'.$i]))
		{
			for($j = 1; $j < $i; $j++)
			{
				if($_POST['favorite-'.$i] == $_POST['favorite-'.$j])
				{
					$repeat_flag = true;
					break;
				}
			}
			if($repeat_flag)
			{
				$repeat_flag = false;
				$i++;
				continue;
			}
			else
			{
				$favorite_threads_array[$next_free_index] = $_POST['favorite-'.$i];
				$next_free_index++;
				$i++;
			}
				
		}
		
		if(!$db_result = $db_connection->query("SELECT connection_id FROM connection_user_thread WHERE connection_user_id = '{$_SESSION['user_id']}' AND connection_thread_id IN ('{$favorite_threads_array[0]}', '{$favorite_threads_array[1]}', '{$favorite_threads_array[2]}', '{$favorite_threads_array[3]}', '{$favorite_threads_array[4]}')"))
			throw new Exception("Błąd serwera", 1);
		
		if($db_result->num_rows != $next_free_index)
			throw new Exception("Access Denied", 11);
		
		$thread_names = printThreadNamesId($db_connection, $_SESSION['user_id']);
		
		$threads_counter = count($thread_names);
		
		$j = 1;
		while($next_free_index < 5 AND $next_free_index < $threads_counter)
		{
			$found_flag = false;
			
			for($i = $next_free_index-1; $i >= 0; $i--)
			{
				if($favorite_threads_array[$i] == $thread_names[$j][0])
				{
					$found_flag = true;
					break;
				}
			}
			
			if(!$found_flag)
			{
				$favorite_threads_array[$next_free_index] = $thread_names[$j][0];
				$next_free_index++;
			}
			$j++;
		}
		
		favoriteThreadsSet($db_connection, $_SESSION['user_id'], $favorite_threads_array);
	}
	catch(Exception $error)
	{
		echo $error->getCode();
	}
	
	db_close($db_connection);
	header('Location: panel.php');
?>