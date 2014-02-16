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
  }

?>


<html>
<head>
	<title>mtng</title>
</head>

<body>
  <form action='comment.php' method="post">
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
            '<div>', $user['first_name'], ' ',
            $user['family_name'], ' (',
            $row['timestamp'], '): ',
            $row['text'],'</div>';
      	}
      }

      if ($recipient_id == 0) {
        // It is the first time the link is seen by the organiser.
        // Ask his details
        echo
          '<input type="text" name="first_name" id="first_name" placeholder="first name"></input>',
          '<input type="text" name="family_name" id="family_name" placeholder="family name"></input>',
          '<input type="text" name="phone" id="phone" placeholder="phone"></input>',
          '<input type="text" name="email" id="email" placeholder="email"></input>';
      } else {
        // The person already has an id and is attached to this link.
        // Attach him to the comment (through hidden field)
        echo '<input name="author_id" type="hidden" value="',$res['recipient_id'],'">';
      }
    ?>
    <textarea name="comment" id="comment" placeholder="Comment"></textarea>
    <?php
      // also send the hash and meeting id (through hidden fields)
      echo '<input name="hash" type="hidden" value="',$hash,'">';
      echo '<input name="meeting_id" type="hidden" value="',$meeting_id,'">';
    ?>
    <input type='submit' id="submit" value="Send">
	</form>

</body>
</html>