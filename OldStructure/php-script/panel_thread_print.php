<?php
	if(count(get_included_files()) == 1)
	{
		exit("Access denied.");
	}

		try
		{
			$user_id = $_SESSION['user_id'];
			
			require_once("php-script/db_connect.php");
			require_once("php-script/print_data.php");
			
	
			if(!$db_connection = db_connect())
			{
				throw new Exception("Błąd serwera", 1);
			}
			
			$favorite_threads = favoriteThreadsGet($db_connection, $_SESSION['user_id']);
			
			if(!$db_query_result = $db_connection->query("SELECT thread_id, thread_name FROM connection_user_thread INNER JOIN thread_data ON connection_user_thread.connection_thread_id = thread_data.thread_id WHERE connection_user_thread.connection_user_id = '$user_id' AND thread_id IN ('{$favorite_threads[0]}', '{$favorite_threads[1]}', '{$favorite_threads[2]}', '{$favorite_threads[3]}', '{$favorite_threads[4]}') ORDER BY FIND_IN_SET(thread_id, '{$favorite_threads[0]},{$favorite_threads[1]},{$favorite_threads[2]},{$favorite_threads[3]},{$favorite_threads[4]}')"))
			{
				throw new Exception("Błąd serwera", 2);
			}
			else
			{
				$thread_html = "";
				
				if($_SESSION['user_temporary_flag'])
				{
					$db_result_row = $db_query_result->fetch_assoc();
					$thread_active_name = $db_result_row['thread_name'];
				}
				else
				{
					for($i = $db_query_result->num_rows; $i > 0; $i--)
					{
						$db_result_row = $db_query_result->fetch_assoc();
						
						if($_SESSION['user_active_thread'] == $db_result_row['thread_id'])
						{	
							$temp_html = '<li class="active-thread"><a href="change_active_thread.php?id='.$db_result_row['thread_id'].'">'.$db_result_row['thread_name']."</a><br></li>";
						}
						else
						{
							$temp_html = '<li class="inactive-thread"><a href="change_active_thread.php?id='.$db_result_row['thread_id'].'">'.$db_result_row['thread_name']."</a><br></li>";
						}
						$thread_html =  $thread_html.$temp_html;
						
					}
					$thread_html = '<nav class="sidemenu d-none d-lg-block" id="sidemenu"><ul>'.$thread_html.'<li onclick="showDialogBox(\'add-thread\')"><a id="create-thread">+ Utwórz</a></li></ul></nav>';
				}
				$db_query_result->close();
			}
			
			$thread_active_name = "";
			
			if(!$db_result = $db_connection->query("SELECT thread_name FROM thread_data WHERE thread_id = '{$_SESSION['user_active_thread']}'"))
			{
				throw new Exception("Błąd serwera", 3);
			}
			if($db_result->num_rows == 1)
			{
				$thread_active_name = $db_result->fetch_assoc()['thread_name'];
			}
		}
		catch(Exception $error)
		{
			echo $error->getMessage();
		}
		db_close($db_connection);
?>