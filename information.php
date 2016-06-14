<?php
require_once('config.php');
if(!isset($user->id)) {
	header('Location: '.DIR.'index.php');
}
$user->GetUserData();
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>
			<?php echo($user->user_name);?>'s Information
		</title>
		<link rel="stylesheet" type="text/css" href="assets/styles.css" />
	</head>
	<body>
		<h1>
			<?php echo($user->user_name);?>'s Information
		</h1>
		<h3>
			<?php echo($user->user_name);?> Said
		</h3>
		<blockquote>
			<?php echo($user->user_comment);?>
		</blockquote>
		<h3>
			<?php echo($user->user_name);?>'s Favorite Film
		</h3>
		<p>
			<i>
				<?php echo($user->user_favorite_film);?>
			</i>
		</p>
		<?php if (sizeof($user->user_rated_films) > 0) {?>
		<h3>
			<?php echo($user->user_name);?>'s Rated Films
		</h3>
		<p>
			<ul>
				<?php 
				foreach($user->user_rated_films as &$film) {
				?>
				<li>
					<?php echo($film['film_name'].": ".$film['rating_text']);?>
				</li>	
				<?php
				}
				?>
			</ul>
		</p>
		<?php }?>
		
		<a href="logout.php">Logout</a>
	</body>
</html>