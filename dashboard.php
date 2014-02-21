<?php
  require_once ('auth.php');
  require_once ('connect.php');

  auth("dashboard.php");

  $user_id = $_SESSION['SESS_USER_ID'];

  // get meetings query
  $qry = "SELECT * FROM meetings WHERE orga_id = '$user_id' OR absentee_id = '$user_id' ORDER BY id DESC";
  $result = @mysql_query($qry);
?>

<!DOCTYPE html>
<html lang="en">
<head>

	<title>mtng</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="style.css">
	<script language="javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script language="javascript" type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js"></script>
</head>

<body>

	<div id="box">
		<h1>Create New Meeting</h1>

		<form action='link.php' id="login" method='post'>
      <input class="text_field" type="text" name="meeting_name" id="meeting_name" placeholder="Meeting name"></input>
      <input class='button' type='submit' value='create'></input>
		</form>
    <?php
      if ($result) {
    	  // Iterate through the meetings
    	  while ($row = @mysql_fetch_assoc($result)){
          $meeting_id = $row['id'];
          $meeting_name = $row['title'];
          $meeting_hash = $row['hash'];
          $meeting_url = "meeting.php?h=".$meeting_hash;

          echo '<a href=', $meeting_url, '><div class="meeting">', $meeting_name, '</div></a>';
    	  }
      }
    ?>
	</div>
</body>
</html>