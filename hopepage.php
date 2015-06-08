<html>
<body>

<!-- Link to the CSS stylesheet -->
	<head>
		<link rel="stylesheet" href="styling.css">
	</head>

<!-- This is the TITLE div -->
	<div class="outer">
		<br>
		<div class="inner-title">
			<br>
			<img src="fridge.jpg" alt="Open Fridge" style="width:100px; height:150px">
			<h1>Welcome to FridgeFinder!</h1>
			<h3>Log-in, upload your favorite ingredients and recipes, and search our database for recipes to match ingredients in your fridge!</font></h3>
			<br>
		</div><br>
	</div>

<!-- User Login / Make Account -->	
	<div class="outer">
		<div class="inner-login">
			<form action="userAccount.php" method="POST">
				<br>
				If you do not already have a FridgeFinder account, you can get started by entering a suitable username in the box below and click "SignUp"!
				<br><br>
				Username: <input type="text" name="username" required> <br>
				&nbspPassword: <input type="text" name="password" required> <br><br>
						  <input type="submit" name="Login" value="Login">&nbsp <input type="submit" name="SignUp" value="SignUp">
				<br><br>
			</form>
		</div>
	</div>
</body>
</html>
