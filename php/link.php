<?php
	require_once('config.php');

	//Connect to mysql server
	$connection = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$connection) {
		die('Failed to connect to server: ' . mysql_error());
	}

	//Select database
	$db = mysql_select_db(DB_DATABASE, $connection);
	if(!$db) {
		die("Unable to select database");
	}

  //Function to sanitize values received from the form. Prevents SQL injection
  function clean($str) {
  	$str = @trim($str);
  	if(get_magic_quotes_gpc()) {
  		$str = stripslashes($str);
  	}
  	return mysql_real_escape_string($str);
  }

  $first_name = clean($_POST['first_name']);
  $family_name = clean($_POST['family_name']);
  $phone = clean($_POST['phone']);
  $email = clean($_POST['email']);
  $meeting = clean($_POST['meeting']);


  // Create SELECT query to see if user exists
  $select_user_qry = "SELECT id FROM users WHERE first_name='$first_name'";
  $result = @mysql_query($select_user_qry);
    // Check whether the query was successful or not
    if ($result && mysql_result($result,0)){
      // User already exists
      $user_id = mysql_result($result,0);
    } else {
      // Create INSERT query to create new user
      $create_user_qry = "INSERT INTO users(first_name, family_name, phone, email) VALUES('$first_name','$family_name','$phone','$email')";
      $create_user_result = @mysql_query($create_user_qry);
      $user_id = mysql_insert_id();
    }
  // create INSERT query to create new meeting
  $create_meeting_qry = "INSERT INTO meeting(title) VALUES('$meeting')";
  $create_meeting_result = @mysql_query($create_meeting_qry);
  $meeting_id =  mysql_insert_id();
  $link = $user_id . " " . $meeting_id;
?>

<html>
<head>
	<title>mtng</title>
</head>

<body>
  <?php
    echo '<div> Please share the following link to your meeting organizer. This link is personal to him and will let him contact you easily if your input is needed:</br>',
    'http://www.mtng.eu/meeting?h=' . $link,
    '</div>';
  ?>
</body>
</html>