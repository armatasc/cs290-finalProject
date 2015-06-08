<html>
<body>

<!-- Link to the CSS stylesheet -->
	<head>
		<link rel="stylesheet" href="styling.css">
	</head>

<!-- User Account -->	
	
	<?php 
		if(isset($_GET['username'])) {
			$username = $_GET['username'];
		}
		
		// Connect to DB
		$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "armatasc-db", "98xf7xW8S5pkhLuR", "armatasc-db");
		if ($mysqli->connect_errno) {
			echo "We could not access our server at this time. We apologize for any inconvenience.<br>";
		}
		
		// Step 1: prepare statement
		if(!($stmt = $mysqli->prepare("SELECT DISTINCT username FROM userAccounts"))) {
			echo "Oops, something went wrong trying verify your username and password!<br>";
		} 
		// Step 2: execute
		if (!$stmt->execute()) {
			echo "Oops, something went wrong trying verify your username and password!<br>";
		}
		// Step 3: create variables and bind results
		$out_filter = NULL;
		if(!$stmt->bind_result($out_username)) {
			echo "Oops, something went wrong trying verify your username and password!<br>";
		}
	
		// Fetch remaining categories in database as drop down options
		$flag = 0;
		while($stmt->fetch()){
			$val = $out_username;
			if ($val == $username) {
				$flag = 1;
				break;
			}
		}
		
		// ACCOUNT INFO CORRECT
		if ($flag == 1): ?>
			
			<!-- GREETING -->
			<div class="outer">
				<div class="inner-login">
					<br>
					<img src="fridge.jpg" alt="Open Fridge" style="width:100px; height:150px">
					<h2>Welcome back to FridgeFinder, <?php echo $_GET['username'];?>!</h2>
					<h3>Enter your favorite ingredients to recipes and search through all of the recipes in our expansive database!</font></h3>
					<br>
				</div>
			</div>	
			
			<!-- ENTER NEW INGREDIENT -->
			<div class="outer">
				<div class="inner-login">
					<br>
					<form action="personalInformation.php" method="GET">
						<h3>Would you like to enter a new recipe or new ingredient for a recipe?</h3>
						
						Recipe Name: <input type="text" name="rName" required> <br>
						&nbsp&nbsp&nbspIngredient: <input type="text" name="ingredient" required>
						<input type="hidden" name="username" value="<?php print $username; ?>">
						<br><br>
						<input type="submit" name="ingredientButton" value="Save Ingredient">
					</form>
					<br>
				</div>
			</div>
			
			<!-- Search ALL ingredients for recipe matches-->
			<div class="outer">
				<div class="inner-login">
					<br>
					<form action="personalInformation.php" method="GET">
						<h3>Filter through every recipe available on FridgeFinder based on an ingredient in your fridge!</h3>
						Ingredient: <input type="text" name="ingredient" required>
						<input type="hidden" name="username" value="<?php print $username; ?>">
						<br><br>
						<input type="submit" name="filterButton" value="Find Recipes">
						<br><br>
						<div class="bar"> </div>
						
						<?php
							$username = $_GET['username'];
						
							// Connect to DB
							$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "armatasc-db", "98xf7xW8S5pkhLuR", "armatasc-db");
							if ($mysqli->connect_errno) {
								echo "Sorry, we could not connect to our FridgeFinder server.";
							} 
							
							// FILTER INPUT 
							if (isset($_GET['filterButton'])) {
								$ingredient = htmlspecialchars($_GET['ingredient']);
								
								// Make RECIPE LISTS if ingredient is present
								// Step 1: prepare statement
								if(!($stmt = $mysqli->prepare("SELECT recipeName, ingredient FROM ingredients ORDER BY recipeName;"))) {
									echo "Prepare Select: Step 1 failed: ". $stmt->errno . " : ". $stmt->error."<br>";
								} 
								// Step 2: execute
								if (!$stmt->execute()) {
									echo "Execute Select: Step 2 failed: ". $stmt->errno . " : ". $stmt->error."<br>";
								}
								// Step 3: create variables and bind results
								$out_filter = NULL;
								if(!$stmt->bind_result($out_rName, $out_ingredient)) {
									echo "Result binding failed: ". $stmt->errno . " : ". $stmt->error."<br>";
								}
						
								// Begin making list for INGREDIENTS FILTER
								$temp;
								while ($stmt->fetch()) {
									if ($ingredient == $out_ingredient || $temp == $out_rName) {
										if($ingredient == $out_ingredient) {
											echo '</ul>';
											echo '<h4>'.$out_rName.'</h4>';
											echo '<ul><li>'.$out_ingredient.'</li>';
											$temp = $out_rName;
										} 
										else {
											echo '<li>'.$out_ingredient.'</li>';
											$temp = $out_rName;
										}
									}
								}		
							}
						?>
					<br>
				</div>
			</div>
			
			<!-- PERSONAL RECIPES -->
			<div class="outer">
				<div class="inner-login">
					<br>
					<!-- PULL ALL RECIPES -->
					<?php 
						$username = $_GET['username'];
						
						// Connect to DB
						$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "armatasc-db", "98xf7xW8S5pkhLuR", "armatasc-db");
						if ($mysqli->connect_errno) {
							echo "Sorry, we could not connect to our FridgeFinder server.";
						} 
						
						// NEW INGREDIENT -> Check if new ingredients have been entered
						if(isset($_GET['ingredientButton'])) {
							$rName = htmlspecialchars($_GET['rName']);
							$ingredient = htmlspecialchars($_GET['ingredient']);
							$uID;
							
							// get uID
							if(!($stmt = $mysqli->prepare("SELECT uID FROM userAccounts WHERE username='$username'"))){
								echo "Oops, something went wrong trying to save your new ingredient!<br>";
							} else {
								$stmt->execute();
								if(!$stmt->bind_result($out_uID)) {
									echo "Oops, something went wrong trying to log into your account!<br>";;
								}
								while ($stmt->fetch()) {
									$uID = $out_uID;
								}
								$stmt->close();
							}
							// push information into database
							if(!($stmt = $mysqli->prepare("INSERT INTO ingredients(uID, recipeName, ingredient) VALUES('$uID', '$rName', '$ingredient')"))){
								echo "Oops, something went wrong trying to log into your account!<br>";
							} else {
								$stmt->execute();
								$stmt->close();
							}
						}
						
						// LIST OF PERSONAL RECIPES
						// Step 1: prepare statement
						if(!($stmt = $mysqli->prepare("SELECT DISTINCT U.username, I.recipeName, I.ingredient FROM userAccounts U, ingredients I WHERE U.username='$username' AND I.uID=U.uID;"))) {
							echo "Oops, something went wrong trying to find your recipes!<br>";
						} 
						// Step 2: execute
						if (!$stmt->execute()) {
							echo "Oops, something went wrong trying to find your recipes!<br>";
						}
						// Step 3: create variables and bind results
						$out_filter = NULL;
						if(!$stmt->bind_result($out_username, $out_rName, $out_ingredient)) {
							echo "Oops, something went wrong trying to find your recipes!<br>";
						}
				
						// Begin making list for personal ingredients
						echo '<h3>Personal Recipes</h3>';
						
						$temp;
						while ($stmt->fetch()) {
							if($temp == $out_rName) {
								echo '<li>'.$out_ingredient.'</li>';
								$temp = $out_rName;
							} else {
								echo '</ul>';
								echo '<h4>'.$out_rName.'</h4>';
								echo '<ul><li>'.$out_ingredient.'</li>';
								$temp = $out_rName;
							}
						}						
					?>
				<br>	
				</div>
			</div>	
			
		
		<!-- ACCOUNT INFO INCORRECT -->
		<?php else: ?>
			<div class="outer">
				<div class="inner-login">				
					<br>We are sorry, but <?php echo $_GET['username'];?> does not match to an account! <br><br>
						Please return to the <a href="http://web.engr.oregonstate.edu/~armatasc/cs290/finalProject/homepage.php" target="_self">homepage</a>.<br><br>
				</div>
			</div>	
		<?php endif; ?>	

</body>
</html>
