<?php 
require_once('config.php');
$statement = $db->prepare("SELECT * FROM film");

$films = array();
if ($statement->execute()) {
	while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
		$films[] = $row;
	}
}

$statement = $db->prepare("SELECT * FROM rating");
$ratings = array();
if ($statement->execute()) {
	while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
		$ratings[] = $row;
	}
}

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>
			User Registration
		</title>
		<link rel="stylesheet" type="text/css" href="assets/styles.css" />
	</head>
	<body>
		<form action="register.php" method="post" name="registrationForm">
			<h1>
				User Registration Form
			</h1>
			<section>
				<label for="username">Username</label>
				<input type="text" name="username"/>
			</section>
			<section>
				<label for="password">Password</label>
				<input type="password" name="password"/>
			</section>
			<section>
				<label for="confirmPassword">Re-Enter Password</label>
				<input type="password" name="confirmPassword"/>
			</section>
			<section>
				<label for="comment">Comment</label>
				<textarea rows="4" cols="50" name="comment"></textarea>
			</section>
			<section>
				<label for="favoriteMovie">Favorite Movie</label>
				
				<select name="favoriteMovie">
					<option	value="-1">---Select a favorite movie---</option>
					<?php 
					foreach ($films as &$film) {
					?>
					<option value="<?php echo($film['film_id']);?>">
						<?php echo($film['film_name']);?>
					</option>
					<?php 
					}
					?>			
				</select>
			</section>
			<section>
				<h2>
					Rate the films you check
				</h2>
				<?php 
				foreach ($films as &$film) {
				?>
				<div>
					<label>
						<input type="checkbox" name="film[]" value="<?php echo($film['film_id'])?>" />
						<?php echo($film['film_name'])?>
					</label>
				</div>
				<div>
					<label>Rating:</label>
					<?php
					foreach ($ratings as &$rating) {
						
					?>
						
						<input name="film<?php echo($film['film_id']);?>rating" type="radio" value="<?php echo($rating['rating_id']);?>"/>
					
					<?php 
					echo($rating['rating_text']);
					}
					?>
					<hr/>
				</div>
				<?php 	
				}
				?>
		
			</section>
			<input type="submit" value="Register"/>
		</form>
	</body>
</html>