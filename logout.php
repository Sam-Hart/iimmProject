<?php 
require_once('config.php');
$user->Logout();

header('Location: '.DIR.'index.php');
exit;
?>