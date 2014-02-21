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
	$first_name = clean($_POST['first_name']);
	$last_name = clean($_POST['last_name']);
  $phone = clean($_POST['phone']);

  $redirect = clean($_POST['redirect']);

  if (!$redirect || trim($redirect) == '') {
    $redirect = "dashboard.php";
  }

  // Check the email doesn't exit already
  $qry = "SELECT * FROM users WHERE email='$email'";
	$result = mysql_query($qry);
	if ($result) {
    if (mysql_num_rows($result) == 1) {
        // user already exists
        header("location: login.php");
        exit();
    } else {
    	//Create query
    	$qry = "INSERT INTO users(first_name, last_name, phone, email, password) VALUES('$first_name', '$last_name', '$phone', '$email', '$md5')";
    	$result = mysql_query($qry);
    	if ($result) {
        session_regenerate_id();
        $_SESSION['SESS_USER_ID'] = mysql_insert_id();
  			session_write_close();
  			header("location: ".$redirect);
        exit();
    	} else {
    	  die("Query failed");
    	}
    }
  } else {
    die("query failed");
  }
?>