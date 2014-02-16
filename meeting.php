<?php
  require_once('connect.php');
  require_once('clean.php');

  $hash = clean($_GET['h']);

  $select_hash_qry = "SELECT * FROM link WHERE hash='$hash'";
  $select_hash_result = @mysql_query($select_hash_qry);
  if ($select_hash_result) {
    $res = mysql_fetch_assoc($select_hash_result);

    $user_id = $res['user_id'];
    $meeting_id = $res['meeting_id'];

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
      	// Iterate through the rows
      	while ($row = @mysql_fetch_assoc($select_comments_result)){
          $user_id = $row['author_id'];
          $select_author_qry = "SELECT * FROM users WHERE id='$user_id'";
          $select_author_result = @mysql_query($select_author_qry);
          $user = mysql_fetch_assoc($select_author_result);
          echo
            '<div>', $user['first_name'], ' ',
            $user['family_name'], ' (',
            $row['timestamp'], '): ',
            $row['text'],'</div>';
      	}
      }
      if ($user_id == 0) {
        echo
          '<input type="text" name="first_name" id="first_name" placeholder="first name"></input>',
          '<input type="text" name="family_name" id="family_name" placeholder="family name"></input>',
          '<input type="text" name="phone" id="phone" placeholder="phone"></input>',
          '<input type="text" name="email" id="email" placeholder="email"></input>';
      } else {
        echo '<input name="author_id" type="hidden" value="',$res['user_id'],'">';
      }
    ?>
    <textarea name="comment" id="comment" placeholder="Comment"></textarea>
    <?php
      echo '<input name="hash" type="hidden" value="',$hash,'">';
      echo '<input name="meeting_id" type="hidden" value="',$meeting_id,'">';
    ?>
    <input type='submit' id="submit" value="Send">
	</form>

</body>
</html>