<?php
  function notify($recipient_id, $notif_medium, $new_comment, $meeting_name, $hash) {
    require_once('connect.php');
    require_once('sms.php');
    require_once('email.php');

    if ($recipient_id) {
      // Get the recipient details
      $select_user_qry = "SELECT * FROM users WHERE id='$recipient_id'";
      $select_user_result = @mysql_query($select_user_qry);
      $recipient = mysql_fetch_assoc($select_user_result);

      $link = 'http://www.mtng.eu/meeting.php?h=' . $hash;

      // Notify
      if($notif_medium == 'phone'){
        $phone = $recipient['phone'];
      	$message="Your help is required on a meeting. Follow this link to help them: $link";
				sendSMS($phone,$message);
      }
      if($notif_medium == 'email'){
        $email = $recipient['email'];
      	$message="Here is a new comment regarding $meeting_name:\n
      						$new_comment\n
      						$link\n
      					 ";
      	sendEmail($email,$message);
      }
    }
  }
?>