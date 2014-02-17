<?php
function sendEmail($to,$message){
	$headers="From: noreply@mtng.eu\n
	$subject="Your question has been answered on mtng.eu";
	mail($to,$subject,$message,$headers);
}
?>