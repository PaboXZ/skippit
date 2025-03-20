<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/main.css"/>
	<link rel="stylesheet" href="css/index.style.css"/>
	<link rel="stylesheet" href="css/fontello.css"/>
	
	<title>Skippit - Task manager</title>
	<link rel="icon" type="image/x-icon" href="favicon/favicon-32x32.png"/>
	
	<style><?=isset($error_style) ? $error_style : ""?><?=isset($message_style) ? $message_style : ""?></style>
	<script src="js/dialog.box.js"></script>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<script>
       function onSubmit(token) {
         document.getElementById("register-form").submit();
       }
     </script>
</head>
