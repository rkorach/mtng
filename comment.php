<?php
  require_once('auth.php');
  require_once('connect.php');
  require_once('clean.php');
  require_once('notification.php');

  auth();

  $user_id = $_SESSION['SESS_USER_ID'];

  // Last comment
  $new_comment = clean($_POST['comment']);
  $meeting_id = clean($_POST['meeting_id']);

  // Create comment from the typed text
  $create_comment_qry = "INSERT INTO comments(text, author_id, meeting_id) VALUES('$new_comment','$user_id', '$meeting_id')";
  $create_comment_result = @mysql_query($create_comment_qry);
  $comment_id = mysql_insert_id();

  // Get the other member to notify him
  $qry = "SELECT * FROM meetings WHERE id='$meeting_id'";
  $result = @mysql_query($qry);
  if ($result) {
    $meeting = mysql_fetch_assoc($result);
    $hash = $meeting['hash'];
    $meeting_name = $meeting['title'];

    if ($meeting['orga_id'] == $user_id) {
      $recipient_id = $meeting['absentee_id'];
      $notif_medium = "phone";
    } else {
      $recipient_id = $meeting['orga_id'];
      $notif_medium = "email";
    }

    // Notify
    notify($recipient_id, $notif_medium, $new_comment, $meeting_name, $hash);
  }

  // Send back to display meeting.php?h=HaSh where the history of comments appear
  header("location: meeting.php?h=".$hash);
?>