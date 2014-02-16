<?php
  require_once('connect.php');
  require_once('clean.php');

  // Last comment
  $new_comment = clean($_POST['comment']);
  $hash = clean($_POST['hash']);
  $author_id = clean($_POST['author_id']);
  $meeting_id = clean($_POST['meeting_id']);

  if (!$author_id) {
    // It was the first time the organiser went on the link
    // get his details
    $first_name = clean($_POST['first_name']);
    $family_name = clean($_POST['family_name']);
    $phone = clean($_POST['phone']);
    $email = clean($_POST['email']);

    // See if user exists
    $select_user_qry = "SELECT id FROM users WHERE phone='$phone' OR email='$email'";
    $result = @mysql_query($select_user_qry);
    if ($result && mysql_result($result,0)){
      // User already exists
      $author_id = mysql_result($result,0);
    } else {
      // Create new user
      $create_user_qry = "INSERT INTO users(first_name, family_name, phone, email) VALUES('$first_name','$family_name','$phone','$email')";
      $create_user_result = @mysql_query($create_user_qry);
      $author_id = mysql_insert_id();

      // Attach this new user as recipient of the corresponding link
      $link_update_qry = "UPDATE link SET recipient_id='$author_id' WHERE hash='$hash'";
      $link_update_result = @mysql_query($link_update_qry);
    }
  }

  // Create comment from the typed text
  $create_comment_qry = "INSERT INTO comments(text, author_id, meeting_id) VALUES('$new_comment','$author_id', '$meeting_id')";
  $create_comment_result = @mysql_query($create_comment_qry);
  $comment_id = mysql_insert_id();

  // Get the creator of this link to notify him
  $link_creator_qry = "SELECT creator_id FROM link WHERE hash='$hash'";
  $link_creator_result = @mysql_query($link_creator_qry);
  if ($link_creator_result && mysql_result($link_creator_result,0)) {
    // Store the needed values for notification in superglobal variable
    $_POST['notified_user_id'] = mysql_result($link_creator_result,0);
    // The sender of the notification is the recipient of this link
    $_POST['sending_user_id'] = $recipient_id;
    $_POST['new_comment'] = $new_comment;
    $_POST['meeting_id'] = $meeting_id;

    // Notify
    include_once('notification.php');
  }

  // Send back to display meeting.php?h=HaSh where the history of comments appear
	header("location: meeting.php?h=".$hash);
?>