<?php
function sendEmail($to,$message){
	$hearders="From: noreply@mtng.eu\n
						 Bcc: contact@cottontracks.com\n"; //Bcc contact@ for the test	
	$subject="Your question has been answered on mtng.eu";
	mail($to,$subject,$message,$headers);
}
?>