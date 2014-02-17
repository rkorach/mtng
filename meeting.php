<?php
  require_once('connect.php');
  require_once('clean.php');

  // Get hash of this link from the url
  $hash = clean($_GET['h']);

  // Get the associated link object in db
  $select_hash_qry = "SELECT * FROM link WHERE hash='$hash'";
  $select_hash_result = @mysql_query($select_hash_qry);
  if ($select_hash_result) {
    $res = mysql_fetch_assoc($select_hash_result);

    // Creator of this link
    $creator_id = $res['creator_id'];
    // Recipient of this link, namely the person currently watching it
    $recipient_id = $res['recipient_id'];
    $meeting_id = $res['meeting_id'];

    // Get all comments for this meeting between these two people
    $select_comments_qry = "SELECT * FROM comments WHERE meeting_id='$meeting_id' ORDER BY timestamp ASC";
    $select_comments_result = @mysql_query($select_comments_qry);

    // Get meeting name
    $select_meeting_qry = "SELECT title FROM meeting WHERE id='$meeting_id'";
    $select_meeting_result = @mysql_query($select_meeting_qry);
    if ($select_meeting_result) {
      $meeting_name = mysql_result($select_meeting_result, 0);
    }
  }

?>


<!DOCTYPE html>
<html lang="en">
<head>

	<title>mtng</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>

	<div id="box">
		<h1><?php echo $meeting_name;?></h1>

    <?php
      if ($select_comments_result) {
      	// Iterate through the comments
      	while ($row = @mysql_fetch_assoc($select_comments_result)){
          $author_id = $row['author_id'];
          // Get the author of the comment
          $select_author_qry = "SELECT * FROM users WHERE id='$author_id'";
          $select_author_result = @mysql_query($select_author_qry);
          $user = mysql_fetch_assoc($select_author_result);

          // Display author, timestamp and comment
          echo
            '<h2>', $user['first_name'], ' ', $user['last_name'], '</h2>',
            '<h3> (', $row['timestamp'], ')</h3>',
            '<p>', $row['text'], '</p>';
      	}
      }
    ?>

    <form action='comment.php' method="post">
      <?php
        if ($recipient_id == 0) {
          // It is the first time the link is seen by the organiser.
          // Ask his details
          echo
            '<input class="text_field" type="text" name="first_name" id="first_name" placeholder="first name"></input>',
            '<input class="text_field" type="text" name="last_name" id="last_name" placeholder="family name"></input>',
            '<input class="text_field" type="text" name="email" id="email" placeholder="email"></input>';
        } else {
          // The person already has an id and is attached to this link.
          // Attach him to the comment (through hidden field)
          echo '<input name="author_id" type="hidden" value="',$res['recipient_id'],'">';
        }
      ?>
      <textarea name="comment" class="text_field" id="comment" placeholder='Write your comment here'></textarea>
      <?php
        // also send the hash and meeting id (through hidden fields)
        echo '<input name="hash" type="hidden" value="',$hash,'">';
        echo '<input name="meeting_id" type="hidden" value="',$meeting_id,'">';
      ?>
      <input class='button' type='submit' id="submit" value="Send">
  	</form>

	</div>
</body>
</html>