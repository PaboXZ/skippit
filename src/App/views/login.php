<?php include $this->resolve('/partials/_header.php'); ?>

<body>
	
    <main>
        <div>
            <p class="center text-huge">Skippit<p>
            <p class="center text-mid" >Skip it, or complete it.</p>
        </div>
        <div class="small-box-centered">
            <form class="form-standard" action="/login" method="POST">
                <input value="<?=isset($oldFormData['login']) ? $oldFormData['login'] : ""?>" type="text" placeholder="login" name="login" onfocus="this.placeholder=''" onblur="this.placeholder='login'"/>
                <input value="" type="password" placeholder="password" name="password" onfocus="this.placeholder=''" onblur="this.placeholder='password'"/>
                
                <?php if(isset($errors['password'])) :?>
                <div><?=$errors['password'][0]?></div>
                <?php endif; ?>
                
                <input type="submit" value="Log in"/>
                <?php #CSRF TOKEN
                include $this->resolve('partials/_csrf.php'); ?>
                <div class="reverse-button" onclick="showDialogBox('dialog-box-register')">Register</div>
            </form>
        </div>  
	</main>	
	
    <aside class="blur-background" id="dialog-box-register">
        <div class="small-box-centered bg-tile">
            <div class="dialog-box-close" onclick="closeDialogBox('dialog-box-register')">
                <div class="dialog-box-title"><i class="icon-cancel"></i></div>
            </div>
            <form class="form-standard" id="register-form" action="register.php" method="POST">
                <h4>Register</h4>
                <input value="" type="text" name="user_name" placeholder="login"/>
                <input value=""type="text" name="user_email" placeholder="email"/>
                <input value="" type="password" name="user_password" placeholder="password"/>
                <input type="password" name="user_password_confirm" placeholder="confirm password"/>
                <input type="checkbox" name="tos" id="tos"/>
                <label for="tos">I accept <a class="reverse-button"  href="ToS.pdf">Terms of Service</a></label>
                <input type="submit" value="Register"/>
                <?php #CSRF TOKEN
                    include $this->resolve('partials/_csrf.php'); ?>
            </form>
        </div>
	</aside>
		
	<footer>
        <p>&copy;Mose creations</p>
	</footer>
</body>
</html>