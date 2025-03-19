<?php
	if(count(get_included_files()) == 1)
	{
		exit("Access denied.");
	}

	if(isset($_SESSION['message']))
	{
		$message = $_SESSION['message'];
		unset($_SESSION['message']);
		
		$message_style = "#dialog-box-message{display: block;} #message-text{display: block}";
	}

?>