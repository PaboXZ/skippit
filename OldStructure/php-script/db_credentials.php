<?php
	if(count(get_included_files()) == 1)
	{
		exit("Access denied.");
	}

	$db_host = "localhost";
	$db_name = "Pager";
	$db_user = "root";
	$db_password = "";

?>
	