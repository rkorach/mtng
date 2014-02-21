<?php
  require_once('connect.php');
  require_once('clean.php');

  // Get hash of this link from the url
  $user_id = clean($_GET['uid']);
  $hash = clean($_GET['h']);
  // do nothing if not a correct url
  if (!$hash || trim($hash) == '') {
    exit();
  }

  // Get the associated meeting object in db
  $select_hash_qry = "SELECT * FROM meetings WHERE hash='$hash'";
  $select_hash_result = @mysql_query($select_hash_qry);
  if ($select_hash_result) {
    $res = mysql_fetch_assoc($select_hash_result);
    $meeting_id = $res['id'];

    // Get all comments for this meeting between these two people
    $select_comments_qry = "SELECT * FROM comments WHERE meeting_id='$meeting_id' ORDER BY timestamp ASC";
    $select_comments_result = @mysql_query($select_comments_qry);
  }

  if ($select_comments_result) {
    $comments = array();
  	// Iterate through the comments
  	while ($row = @mysql_fetch_assoc($select_comments_result)){
      $author_id = $row['author_id'];
      if ($author_id == $user_id) {
        $row['author'] = "You:";
      } else {
        // Get the author of the comment
        $select_author_qry = "SELECT * FROM users WHERE id='$author_id'";
        $select_author_result = @mysql_query($select_author_qry);
        $user = mysql_fetch_assoc($select_author_result);
        $row['author'] = $user['first_name'].' '.$user['last_name'].':';
      }
      array_push($comments, $row);
  	}

    echo json_encode($comments);
  }
?>