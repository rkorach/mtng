<?php
  require_once('connect.php');
  require_once('clean.php');

  $new_comment = clean($_POST['comment']);
  $hash = clean($_POST['hash']);
  $author_id = clean($_POST['author_id']);
  $meeting_id = clean($_POST['meeting_id']);

  $recipient_id = clean($_POST['recipient_id']);

  if (!$recipient_id) {
    $first_name = clean($_POST['first_name']);
    $family_name = clean($_POST['family_name']);
    $phone = clean($_POST['phone']);
    $email = clean($_POST['email']);

    // Create SELECT query to see if user exists
    $select_user_qry = "SELECT id FROM users WHERE phone='$phone' OR email='$email'";
    $result = @mysql_query($select_user_qry);
    // Check whether the query was successful or not
    if ($result && mysql_result($result,0)){
      // User already exists
      $author_id = mysql_result($result,0);
    } else {
      // Create INSERT query to create new user
      $create_user_qry = "INSERT INTO users(first_name, family_name, phone, email) VALUES('$first_name','$family_name','$phone','$email')";
      $create_user_result = @mysql_query($create_user_qry);
      $author_id = mysql_insert_id();
    }
    $link_update_qry = "UPDATE link SET recipient_id='$author_id' WHERE hash='$hash'";
    $link_update_result = @mysql_query($link_update_qry);
  }

  $create_comment_qry = "INSERT INTO comments(text, author_id, meeting_id) VALUES('$new_comment','$author_id', '$meeting_id')";
  $create_comment_result = @mysql_query($create_comment_qry);
  $comment_id = mysql_insert_id();


  $link_creator_qry = "SELECT creator_id FROM link WHERE hash='$hash'";
  $link_creator_result = @mysql_query($link_creator_qry);
  if ($link_creator_result && mysql_result($link_creator_result,0)) {
     $_POST['notified_user_id'] = mysql_result($link_creator_result,0);
     $_POST['sending_user_id'] = $recipient_id;
     $_POST['new_comment'] = $new_comment;
     $_POST['meeting_id'] = $meeting_id;
     include_once('notification.php');
  }

	header("location: meeting.php?h=".$hash);
?>