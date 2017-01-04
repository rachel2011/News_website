<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link rel = "stylesheet" type = "text/css" href = "login.css">
    </head>

<body>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">



	
	<form action="userMatch.php" method="POST">
		<fieldset>
				<legend id='ti'>User Log in</legend>
		<p> 
			<label id='un' for = "userName">Username:</label>
			<input type = "text" name="userName" id="userName" />
		</p>
		<p>
			<label id='pw'>Password: </label>
			<input type="password" name="password"/>
		</p>
		<p>
			<input type="submit" name="login" value="Login"/>
			<input type="submit" name="register" value="Register"/>
			<input type="submit" name="guest" value="Guest"/>
			<input type="reset" value="Reset"/>
		</p>
		</fieldset>
			</form>

		</div>
	</div>
</div>
	

	<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>

</html>