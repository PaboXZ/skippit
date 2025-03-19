<?php
	if(count(get_included_files()) == 1)
	{
		exit("Access denied.");
	}

		
		require_once('php-script/db_connect.php');
		try
		{
			if(!isset($_SESSION['user_active_thread']))
			{
				throw new Exception("Access Denied", 0);
			}
			
			
			if(!$db_connection = db_connect())
			{
				throw new Exception("Błąd serwera", 11);
			}
			
			if($_SESSION['user_active_thread'] == 0)
			{
				 throw new Exception();
			}				
			
			$user_id = $_SESSION['user_id'];
			$user_active_thread = $_SESSION['user_active_thread'];
			
			if(!$db_query_result = $db_connection->query("SELECT connection_is_owner, connection_delete_permission, connection_create_power FROM connection_user_thread WHERE connection_user_id = '$user_id' AND connection_thread_id = '$user_active_thread'"))
			{
				throw new Exception("Błąd serwera", 12);
			}
			if($db_query_result->num_rows != 1)
			{
				throw new Exception("Błąd serwera", 13);
			}
			
			$db_result_row = $db_query_result->fetch_assoc();
			
			$delete_permission = false;
			$create_power = 0;
			
			if($db_result_row['connection_is_owner'] == 1)
			{
				$delete_permission = true;
				$create_power = 5;
			}
			else
			{
				if($db_result_row['connection_delete_permission'] == 1) $delete_permission = true;
				$create_power = $db_result_row['connection_create_power'];
			}
			
			if($create_power > 0)
			{
				$task_button_html = '	<div id="add-task-button" onclick="showDialogBox(\'add-task\')">
								+ Dodaj wpis
								</div>';
				$priority_levels[0] = array("power-mid-low", "power-mid", "power-mid-high", "power-high");				
				$priority_levels[1] = array("Średnio-niski", "Średni", "Średnio-wysoki", "Wysoki");				
				
				
				if(isset($_SESSION['create_task_return_name']))
				{
					$task_style_r = 'style="display: block;"';
					$task_name_r = $_SESSION['create_task_return_name'];
					$task_content_r =  $_SESSION['create_task_return_content'];
					
					unset($_SESSION['create_task_return_name']);
					unset($_SESSION['create_task_return_content']);
				}
				else{
					$task_name_r = "";
					$task_content_r = "Treść wpisu";
					$task_style_r = "";
				}
								
				$add_task_html = '
				<aside>
					<div class="blur-background" id="add-task"'.$task_style_r.'>
						<div class="container">
							<div class ="row">
								<div class="col-lg-6 offset-lg-3">
									<div class="dialog-box">
										<div class="row">
											<div class="dialog-box-title col-9">
												Tworzenie Wpisu
											</div>
											<div class="dialog-box-title offset-1 col-1 dialog-box-close" onclick="closeDialogBox(\'add-task\')">
												<i class="icon-cancel"></i>
											</div>
											<div class="offset-lg-1 col-lg-10">
												<form action="create_task.php" method="POST">
													Nazwa wpisu:<br>
													<input type="text" name="task_title" placeholder="Nazwa wpisu" value="'.$task_name_r.'"/><br>
													Treść wpisu:<br>
													<textarea name="task_content" rows="6">'.$task_content_r.'</textarea><br>
													Priorytet:<br>
													<input type="radio" name="task_power" value="1" id="power-low" checked/>
													<label for="power-low">Niski</label><br>';
													
				for($i = 2; $i <= $create_power; $i++)
				{
					$add_task_html .= '<input type="radio" name="task_power" value="'.$i.'" id="'.$priority_levels[0][$i-2].'"/>
										<label for="'.$priority_levels[0][$i-2].'">'.$priority_levels[1][$i-2].'</label><br>';
				}
												
				$add_task_html .='
													<br>
													<input type="submit" value="Dodaj wpis"/>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</aside>
			';
			}
			
			

			
			if(!$db_query_result = $db_connection->query("SELECT task_id, task_title, task_content, task_power FROM task_data WHERE task_thread_id = '$user_active_thread' ORDER BY task_power DESC, task_id ASC"))
			{
				throw new Exception("Błąd serwera", 14);
			}
			
			$task_html = "";
			for($i = $db_query_result->num_rows; $i > 0; $i--)
			{
				$db_result_row = $db_query_result->fetch_assoc();
				
				$menu_html = '<ul class="task-menu-list" id="task-menu-list-'.$db_result_row['task_title'].'">';
				
				if($delete_permission) $menu_html .= '<li onclick="deleteTask(\''.$db_result_row['task_title'].'\', \''.$db_result_row['task_id'].'\')">Usuń wpis</li>';
				
				$menu_html .= '</ul>';
							
				
				$temporary_html = '<div class="col-12 col-lg-6">
					<div class="task-show task-power-'.$db_result_row['task_power'].'">
						<div class="row">
							<div class="col-10 col-lg-9 task-title">
								'.$db_result_row['task_title'].'
							</div>
							<div class="task-title-menu offset-1 col-1 offset-lg-1 col-lg-2 col-xl-2" onclick="showTaskMenu(\''.$db_result_row['task_title'].'\')">
								<i class="icon-menu"></i>
								'.$menu_html.'
							</div>

						</div>
					</div>
					<div class="task-show task-power-'.$db_result_row['task_power'].'">
						<div class="task-content">
							'.$db_result_row['task_content'].'
						</div>
					</div>
				</div>';
				
				$task_html .= $temporary_html;
			}
			$db_query_result->close();
		}
		catch(Exception $error)
		{
			echo $error->getMessage();
		}
		db_close($db_connection);
?>