<?php
  require_once('connect.php');
  require_once('clean.php');

  $sender_id = $_POST['sending_user_id'];
  $recipient_id = $_POST['notified_user_id'];
  $new_comment = $_POST['new_comment'];
  $meeting_id = $_POST['meeting_id'];

  if ($user_id) {
    $select_user_qry = "SELECT * FROM users WHERE id='$recipient_id'";
    $select_user_result = @mysql_query($select_user_qry);
    $recipient = mysql_fetch_assoc($select_user_result);

    $phone = $recipient['phone'];
    $email = $recipient['email'];
    $hash = md5($recipient_id.$meeting_id);

    $link_qry = "SELECT * FROM link WHERE recipient_id='$recipient_id' AND meeting_id='$meeting_id";
    $link_result = @mysql_query($link_creator_qry);
    $db_link = mysql_fetch_assoc($link_result)

    if (!$db_link) {
      // create INSERT query to create new url
      $create_link_qry = "INSERT INTO link(hash, creator_id, recipient_id, meeting_id) VALUES('$link', '$sender_id', '$recipient_id', '$meeting_id')";
      $create_link_result = @mysql_query($create_link_qry);
    }

    $link = 'http://www.mtng.eu/meeting.php?h=' . $hash;

    // Do some Twilio / AWS / OVH magic to send the notification
    // Start with email so that we don't have to pay for sms
    // during the whole testing phase
    // $link, $phone, $email, $new_comment are ready to use

    $_POST['sending_user_id'] = null;
    $_POST['notified_user_id'] = null;
    $_POST['new_comment'] = null;
    $_POST['meeting_id']; = null;
  }

?>