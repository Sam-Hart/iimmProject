<?php
	require_once('config.php');
	
	
	$unsuccessfulURL = DIR.'registration.php';
	$successfulURL = DIR.'success.php';
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$errorString = "?";
		$invalid = false;
		if(isset($_POST['username']) AND $_POST['username'] != '') {
			$username = $_POST['username'];
			$userQuery = $db->prepare("SELECT user_name FROM reg_user WHERE user_name = :name");
			$userQuery->execute(array(':name' => strtolower($username)));
			if($row = $userQuery->fetch()) {
				$errorString .= "usernameduplicate=1";
				$invalid = true;
			}

		} else {
			$errorString .= "username=1";
			$invalid = true;
		}
		if(isset($_POST['password']) AND $_POST['password'] != '') {
			$password = $_POST['password'];
		} else {
			$errorString .= "&password=1";
			$invalid = true;
		}
		if(isset($_POST['confirmPassword']) AND $_POST['confirmPassword'] != '') {
			$confirmPassword = $_POST['confirmPassword'];
			if ($confirmPassword != $password) {
				$errorString .= "&repeatPassword=1";
				$invalid = true;
			}
		} else {
			$errorString .= "&confirmPassword=1";
			$invalid = true;
		}
		$filmRatings = array();
		
		if($_POST['favoriteMovie'] != -1) {
			$favoriteMovie = $_POST['favoriteMovie'];	
			
		} else {
			$errorString .= "&favMovie=1";
			$invalid = true;
		}
		
		$comment = $_POST['comment'];
		
		foreach ($_POST['film'] as &$film) {
		
			if(isset($_POST['film'.$film.'rating'])) {
				$filmRatings['film'.$film] = array($film, $_POST['film'.$film.'rating']);
			} else {
				
				$errorString .= "&film".$film."rating=1";
				$invalid = true;
			}
		}
		
		
		if(!$invalid) {
			$register_sql = "CALL prc_user_create(?, ?, ?, ?, @userId, @result)";
			$register_statement = $db->prepare($register_sql);
			
			$data = array($username, $password, $favoriteMovie, $comment);
			$register_statement->execute($data);
			
			$register_statement->closeCursor();
			$output = $db->query("SELECT @userId, @result")->fetch(PDO::FETCH_ASSOC);
			
	
			foreach($filmRatings as &$film) {
				$ratingData = array($output['@userId'], $film[0], $film[1]);
				$rating_sql = "CALL prc_add_user_rated_film(?, ?, ?, @result)";
				$rating_statement = $db->prepare($rating_sql);
				$rating_statement->execute($ratingData);
				$rating_statement->closeCursor();
				
			}
			header('Location: '.$successfulURL);
			exit;
			
			
		} else {
			header('Location: '.$unsuccessfulURL.$errorString);
			exit;
		}
		
		
	}
	
	header('Location: '.$unsuccessfulURL);
	exit;

?>
