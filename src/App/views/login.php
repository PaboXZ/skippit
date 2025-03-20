<?php include $this->resolve('/partials/_header.php'); ?>

<body>
	
    <main>
        <div>
            <p>Skippit<p>
            <p>Skip it, or complete it.</p>
        </div>
        <form class="form-standard" action="login.php" method="POST">
            <input value="" type="text" placeholder="login" name="user_name" onfocus="this.placeholder=''" onblur="this.placeholder='login'"/>
            <input value="" type="password" placeholder="password" name="user_password" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'"/>
            <input type="submit" value="Log in"/>
            <?php #CSRF TOKEN
            include $this->resolve('partials/_csrf.php'); ?>
        </form>
        <div onclick="showDialogBox('dialog-box-register')">Register</div>
	</main>	
	
	
    <aside class="blur-background" id="dialog-box-register">
        <div class="dialog-box-title">Zarejestruj</div>
        <div class="dialog-box-close" onclick="closeDialogBox('dialog-box-register')">
            <div class="dialog-box-title"><i class="icon-cancel"></i></div>
        </div>
        <form id="register-form" action="register.php" method="POST">
            <input value="" type="text" name="user_name" placeholder="login"/>
            <input value=""type="text" name="user_email" placeholder="email"/>
            <input value="" type="password" name="user_password" placeholder="password"/>
            <input type="password" name="user_password_confirm" placeholder="confirm password"/>
            <input type="checkbox" name="tos" id="tos"/>
            <label for="tos">I accept <a href="ToS.pdf">Terms of Service</a></label>
            <input type="submit" value="Register"/>
            <?php #CSRF TOKEN
                include $this->resolve('partials/_csrf.php'); ?>
        </form>
	</aside>
		
	<footer>
        <p>&copy;Mose creations</p>
	</footer>
</body>
</html>