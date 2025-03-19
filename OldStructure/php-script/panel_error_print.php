<?php
	if(count(get_included_files()) == 1)
	{
		exit("Access denied.");
	}

	if(isset($_SESSION['error_create_thread']))
	{
		$error_message = $_SESSION['error_create_thread'];
		$error_style = "#dialog-box-message{display: block;} #error-text{display: block;}";
		unset($_SESSION['error_create_thread']);
		
		$create_thread_name_r = $_SESSION['create_thread_return_name'];
		unset($_SESSION['create_thread_return_name']);
		
		$create_thread_return_style = "display: block;";
	}
	else if(isset($_SESSION['error_change_active_thread']))
	{
		$error_message = $_SESSION['error_change_active_thread'];
		$error_style = "#dialog-box-message{display: block;} #error-text{display: block;}";
		unset($_SESSION['error_change_active_thread']);
	}
	else if(isset($_SESSION['error_task_delete']))
	{
		$error_message = $_SESSION['error_task_delete'];
		$error_style = "#dialog-box-message{display: block;} #error-text{display: block;}";
		unset($_SESSION['error_task_delete']);
	}
	else if(isset($_SESSION['error_create_task']))
	{
		$error_message = $_SESSION['error_create_task'];
		$error_style = "#dialog-box-message{display: block;} #error-text{display: block;}";
		
		unset($_SESSION['error_create_task']);
	}

?>