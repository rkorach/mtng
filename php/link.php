<?php
	require_once('connect.php');
  require_once('clean.php');

  $first_name = clean($_POST['first_name']);
  $family_name = clean($_POST['family_name']);
  $phone = clean($_POST['phone']);
  $email = clean($_POST['email']);
  $meeting = clean($_POST['meeting']);

  // Create SELECT query to see if user exists
  $select_user_qry = "SELECT id FROM users WHERE phone='$phone' OR email='$email'";
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

  $link = md5($user_id.$meeting_id);

  // create INSERT query to create new url
  $create_link_qry = "INSERT INTO link(hash, user_id, meeting_id) VALUES('$link', '','$meeting_id')";
  $create_link_result = @mysql_query($create_link_qry);
?>

<html>
<head>
	<title>mtng</title>
</head>

<body>
  <?php
    echo '<div> Please share the following link to your meeting organizer. This link is personal to him and will let him contact you easily if your input is needed:</br>',
    'http://www.mtng.eu/meeting.php?h=' . $link,
    '</div>';
  ?>
</body>
</html>