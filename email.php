<?php
function sendEmail($to,$message){
	$hearders="From: noreply@mtng.eu";
	$subject="Your question has been answered on mtng.eu";
	mail($to,$subject,$message,$headers);
}
?>