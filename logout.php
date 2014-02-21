<?php
	//Start session
	session_start();

	//Unset the variables stored in session
	unset($_SESSION['SESS_USER_ID']);
  header("location: login.php");
  exit();
?>