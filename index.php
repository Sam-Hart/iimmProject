<?php 
require_once('config.php');
if(isset($_SESSION['user_id'])) {
	header('Location: '.DIR.'information.php');
}


?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>
			User Login &amp; Registration
		</title>
		
	</head>
	<body>
		<form action="login.php" method="post" name="loginForm">
			<h1>Login Form</h1>
			<section>
				<label for="username">Username</label>
				<input type="text" name="username"/>
			</section>
			<section>
				<label for="password">Password</label>
				<input type="password" name="password"/>
			</section>
			
			
			<input type="submit" value="Login"/>
		</form>
		<a href="registration.php">Registration</a>
	</body>
</html>