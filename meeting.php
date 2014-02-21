<?php
  require_once ('auth.php');
  require_once('connect.php');
  require_once('clean.php');

  // Get hash of this link from the url
  $hash = clean($_GET['h']);
  // back to dashboard if not a correct url
  if (!$hash || trim($hash) == '') {
    header("location: dashboard.php");
    exit();
  }

  auth("meeting.php?h=".$hash);

  $user_id = $_SESSION['SESS_USER_ID'];

  // Get the associated meeting object in db
  $select_hash_qry = "SELECT * FROM meetings WHERE hash='$hash'";
  $select_hash_result = @mysql_query($select_hash_qry);
  if ($select_hash_result) {
    $res = mysql_fetch_assoc($select_hash_result);

    $meeting_id = $res['id'];
    $meeting_name = $res['title'];
    // Creator of this link
    $creator_id = $res['absentee_id'];
    $orga_id = $res['orga_id'];
    if ($orga_id == 0 && $creator_id != $user_id) {
      // It is the first time the link is seen by the organiser.
      $meeting_update_qry = "UPDATE meetings SET orga_id='$user_id' WHERE id='$meeting_id'";
      $meeting_update_result = @mysql_query($meeting_update_qry);
    }

    // Get all comments for this meeting between these two people
    $select_comments_qry = "SELECT * FROM comments WHERE meeting_id='$meeting_id' ORDER BY timestamp ASC";
    $select_comments_result = @mysql_query($select_comments_qry);
  }

?>


<!DOCTYPE html>
<html lang="en">
<head>

	<title>mtng</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="style.css">
	<script language="javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script language="javascript" type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js"></script>
	<script>
		$(function() {
			$("#organizerform").validate({
				rules: {
					first_name: "required",
					last_name: "required",
					email: {
						required: true,
						email: true
					},
					comment: "required"
				},
				messages: {
					first_name: "Please enter your first name",
					last_name: "Please enter your last name",
					email: {
						required: "Please enter your email"
					},
					comment: "Please enter your comment",
				}
			});
		});
	</script>

  <script>
  (function(){
    $(document).ready(function(){
      setInterval(function(){
        var lHash = document.location.href.split('h=');
        var sHash = lHash[lHash.length - 1];
        $.get("getcomments.php?h=" + sHash + "&uid=" + <?php echo $user_id;?>, function(lComments){
          lComments = JSON.parse(lComments);
          var iLength = lComments.length;
          var l$Comments = [];
          for (var i = 0; i < iLength; i++) {
            var comment = lComments[i];
            var $comment = $('<div class="comment"></div>');
            var $author = $('<h2></h2>').text(comment['author']);
            var $timestamp = $('<h3></h3>').text('(' + comment['timestamp'] + ')');
            var $text = $('<p></p>').text(comment['text']);
            l$Comments.push($comment.append($author, $timestamp, $text));
          }
          $("#comments").empty().append(l$Comments);
        });
      },10000);
    });
  })();
  </script>

</head>

<body>

	<div id="box">
    <h1><?php echo $meeting_name;?></h1>
  	<div id="comments">


    <?php
      if ($select_comments_result) {
      	// Iterate through the comments
      	while ($row = @mysql_fetch_assoc($select_comments_result)){
          $author_id = $row['author_id'];
          if ($author_id == $user_id) {
            $username = "You:";
          } else {
            // Get the author of the comment
            $select_author_qry = "SELECT * FROM users WHERE id='$author_id'";
            $select_author_result = @mysql_query($select_author_qry);
            $user = mysql_fetch_assoc($select_author_result);
            $username = $user['first_name'].' '.$user['last_name'].':';
          }

          // Display author, timestamp and comment
          echo
            '<div class="comment">',
            '<h2>', $username, '</h2>',
            '<h3> (', $row['timestamp'], ')</h3>',
            '<p>', $row['text'], '</p>',
            '</div>';
      	}
      }
    ?>
  </div>
    <form action='comment.php' id='organizerform' method="post">
      <textarea name="comment" class="text_field" id="comment" placeholder='Write your comment here'></textarea>
      <input name="meeting_id" type="hidden" value="<?php echo $meeting_id;?>">
      <input class='button' type='submit' id="submit" value="Send">
  	</form>

	</div>
</body>
</html>