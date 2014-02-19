<?php
  function notify($sender_id, $recipient_id, $new_comment, $meeting_id) {
    require_once('connect.php');
    require_once('clean.php');
    require_once('sms.php');
    require_once('email.php');

    if ($recipient_id) {
      // Get the recipient details
      $select_user_qry = "SELECT * FROM users WHERE id='$recipient_id'";
      $select_user_result = @mysql_query($select_user_qry);
      $recipient = mysql_fetch_assoc($select_user_result);

      $phone = $recipient['phone'];
      $email = $recipient['email'];
      $hash = md5($sender_id.$meeting_id);

      // get the link for the recipient
      $link_qry = "SELECT * FROM link WHERE recipient_id='$recipient_id' AND meeting_id='$meeting_id'";
      $link_result = @mysql_query($link_qry);
      $db_link = mysql_fetch_assoc($link_result);

      if (!$db_link) {
        // There was no link yet, it is the first notification
        // Create new link object in base
        $create_link_qry = "INSERT INTO link(hash, creator_id, recipient_id, meeting_id) VALUES('$hash', '$sender_id', '$recipient_id', '$meeting_id')";
        $create_link_result = @mysql_query($create_link_qry);
      }

      $link = 'http://www.mtng.eu/meeting.php?h=' . $hash;

      // Get meeting name
      $meeting_name_qry= "SELECT title FROM meeting WHERE id='$meeting_id'";
      $meeting_name_result= @mysql_query($meeting_name_qry);
      $meeting_name= mysql_result($meeting_name_result,0);

      // Notify
      if($phone){
      	$message="Your help is required on a meeting. Follow this link to help them: $link";
				sendSMS($phone,$message);
      }
      if($email){
      	$message="Here is a new comment regarding $meeting_name:\n
      						$new_comment\n
      						$link\n
      					 ";
      	sendEmail($email,$message);
      }
    }
  }
?>