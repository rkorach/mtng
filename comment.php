<?php
  require_once('connect.php');
  require_once('clean.php');

  $new_comment = clean($_POST['comment']);
  $hash = clean($_POST['hash']);
  $author_id = clean($_POST['author_id']);
  $meeting_id = clean($_POST['meeting_id']);

  if (!$user_id) {
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
    $link_update_qry = "UPDATE link SET user_id='$author_id' WHERE hash='$hash'";
    $link_update_result = @mysql_query($link_update_qry);
  }

  $create_comment_qry = "INSERT INTO comments(text, author_id, meeting_id) VALUES('$new_comment','$author_id', '$meeting_id')";
  $create_comment_result = @mysql_query($create_comment_qry);
  $comment_id = mysql_insert_id();

	header("location: meeting.php?h=".$hash);
?>