<?php
	//Start session
	session_start();

	//Include database connection details
	require_once('connect.php');
	require_once('clean.php');

	//Sanitize the POST values
	$email = clean($_POST['email']);
	$password = clean($_POST['password']);
  $md5 = md5($password);
  $redirect = clean($_POST['redirect']);

  if (!$redirect || trim($redirect) == '') {
    $redirect = "dashboard.php";
  }

  // Check the email doesn't exit already
  $qry = "SELECT * FROM users WHERE email='$email' AND password='$md5'";
	$result = mysql_query($qry);
	if ($result) {
    if (mysql_num_rows($result) == 1) {
      // Login successful
      session_regenerate_id();
			$user = mysql_fetch_assoc($result);
      $_SESSION['SESS_USER_ID'] = $user['id'];
			session_write_close();
			header("location: ".$redirect);
      exit();
    } else {
			header("location: login.php");
      exit();
    }
  } else {
    die("query failed");
  }
?>