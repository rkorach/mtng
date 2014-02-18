<?php
function sendSMS($to,$message){
	//Seesion OVH
	$user="mtng";
	$pass="a2OsMijk";
	$sms_compte="sms-dj219716-1";
	
	//Our OVH phone number
	$from="COTTONTRACK";
	
	try{
	$soap = new SoapClient("https://www.ovh.com/soapi/soapi-re-1.29.wsdl");
	$result = $soap->telephonySmsUserSend("$user", "$pass", "$sms_compte", "$from", "$to", "$message");
	}
	
	catch(SoapFault $fault){
	echo $fault;
	}
}
?>