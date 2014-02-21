<?php
  require_once('auth.php');
	require_once('connect.php');
  require_once('clean.php');

  auth();

  $user_id = $_SESSION['SESS_USER_ID'];

  $meeting_name = $_POST['meeting_name'];

  // Create new meeting
  $create_meeting_qry = "INSERT INTO meetings(title, absentee_id) VALUES('$meeting_name', '$user_id')";
  $create_meeting_result = @mysql_query($create_meeting_qry);
  $meeting_id =  mysql_insert_id();

  // hash for this user and particular meeting
  $hash = md5($user_id."-".$meeting_id);
  $link = "http://www.mtng.eu/meeting.php?h=".$hash;


  // update meeting with the generated hash
  $meeting_update_qry = "UPDATE meetings SET hash='$hash' WHERE id='$meeting_id'";
  $meeting_update_result = @mysql_query($meeting_update_qry);

?>

<!DOCTYPE html>
<html lang="en">
<head>

	<title>mtng - your link to share</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>
	<div id="box">
		<h1>Share this link with your coworkers:</h1>
		<input class='text_field' type='text' name='link' value=<?php echo $link;?>></input>
    <a href ="dashboard.php">Back to dashboard</a>
	</div>
</body>
</html>