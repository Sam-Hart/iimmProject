<?php
require_once('config.php');
$unsuccessfulURL = DIR.'index.php';
$successfulURL = DIR.'information.php';

if(isset($_SESSION['user_id'])) {
	header('Location: '.$successfulURL);
	exit;
}


if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($_POST['username'] != '' AND isset($_POST['username']) AND isset($_POST['password'])) {
		$user->Login($_POST['username'], $_POST['password']);
		if(isset($user->id)) {
			header('Location: '.$successfulURL);
			exit;
		} else {
			header('Location: '.$unsuccessfulURL);
		}
		
	}
} else {
	header('Location: '.$unsuccessfulURL);
	exit;
}

?>