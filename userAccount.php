<html>
<body>

<!-- Link to the CSS stylesheet -->
	<head>
		<link rel="stylesheet" href="styling.css">
	</head>

<!-- User Account -->	
	<div class="outer">
		<div class="inner-login">
			
			<?php
				// Connect to DB
				$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "armatasc-db", "98xf7xW8S5pkhLuR", "armatasc-db");
				if ($mysqli->connect_errno) {
					echo "Failed to connect to MySQL: ".$mysqli->connect_errno." : ".$mysqli->connect_error."<br>";
				} 
		
				// If username/password set -> check if they exist or make a new account
				if(isset($_POST['username']) && isset($_POST['password'])) {
					$username = htmlspecialchars($_POST['username']);
					$password = htmlspecialchars($_POST['password']);
					
					// SIGN UP
					if(isset($_POST['SignUp'])) {
						// Step 1: prepare statement to check username
						if(!($stmt = $mysqli->prepare("SELECT DISTINCT username FROM userAccounts"))) {
							echo "Oops, something went wrong trying to create your account!<br>";
						} 
						// Step 2: execute
						if (!$stmt->execute()) {
							echo "Oops, something went wrong trying to create your account!<br>";
						}
						// Step 3: create variables and bind results
						$out_filter = NULL;
						if(!$stmt->bind_result($out_filter)) {
							echo "Oops, something went wrong trying to create your account!<br>";
						}
						
						// Fetch remaining categories in database as drop down options
						$flag = 0;
						while($stmt->fetch()){
							$val = $out_filter;
							if ($val == $username) {
								echo 'We are sorry, the username you are trying to use is taken. Please return to the 
									<a href="http://web.engr.oregonstate.edu/~armatasc/cs290/finalProject/homepage.php" target="_self">homepage</a> to try another username.<br><br>';
								$flag = 1;
								break;
							}
						}
						if ( $flag == 0) {
							// INSERT information into userAccount database
							if(!($stmt = $mysqli->prepare("INSERT INTO userAccounts(username, password) VALUES('$username', '$password')"))){
								echo "Oops, something went wrong trying to create your account!<br>";
							} else {
								$stmt->execute();
								$stmt->close();
							}
							
							echo "Thank you ".$username.", for creating an account on FridgeFinder. Now it is time to get cooking! You can start storing your recipes and ingredients 
								by clicking ".'<a href="http://web.engr.oregonstate.edu/~armatasc/cs290/finalProject/personalInformation.php?username='.$username.'" target="_self">here</a>!<br><br>';
						}
					}
					
					
					// LOGIN
					elseif(isset($_POST['Login'])) {
						// Error check input and log user in
						
						// Step 1: prepare statement
						if(!($stmt = $mysqli->prepare("SELECT DISTINCT username, password FROM userAccounts"))) {
							echo "Oops, something went wrong trying to log into your account!<br>";
						} 
						// Step 2: execute
						if (!$stmt->execute()) {
							echo "Oops, something went wrong trying to log into your account!<br>";
						}
						// Step 3: create variables and bind results
						$out_filter = NULL;
						if(!$stmt->bind_result($out_UN, $out_PW)) {
							echo "Oops, something went wrong trying to log into your account!<br>";
						}
					
						// Fetch remaining categories in database as drop down options
						$flag = 0;
						while($stmt->fetch()){
							$val = $out_UN;
							$val2 = $out_PW;
							if ($val == $username && $val2 == $password) {
								$flag = 1;
								break;
							}
						}
						if ($flag == 1) {
							echo "<br>Thank you, ".$username.", your account has been authenticated!".' Click 
								<a href="http://web.engr.oregonstate.edu/~armatasc/cs290/finalProject/personalInformation.php?username='.$username.'" target="_self">here</a> to continue. <br><br>';
						} else {
							echo 'We are sorry, the username and password you entered do not match an account. Please return to the 
								<a href="http://web.engr.oregonstate.edu/~armatasc/cs290/finalProject/homepage.php" target="_self">homepage</a>.<br><br>';
						}
					}
					 // No username/password set -> error msg	
					else {
						echo '<br>This page is unavailable. Please click <a href="http://web.engr.oregonstate.edu/~armatasc/cs290/finalProject/homepage.php" 
							  target="_self">here</a> to return to the login screen.<br>';
					}
				}
			?>
			
		</div>
	</div>

</body>
</html>
	
