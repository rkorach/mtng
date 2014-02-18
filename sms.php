<?php
function sendSMS($to,$message){
	//Seesion OVH
	$nic="mtng";
	$pass="a2OsMijk";
	$sms_compte="sms-dj219716-1";
	
	//Our OVH phone number
	$from="COTTONTRACK";
	
	try
	{
	$soap = new SoapClient("https://www.ovh.com/soapi/soapi-re-1.8.wsdl");
	// $session = $soap->login("$nic", "$pass","fr", false);
	
	//echo "login successfull\n"; //Test connexion
	
	$result = $soap->telephonySmsSend("$nic", "$pass", "$sms_compte", "$from", "$to", "$message");
	
	//echo "telephonySmsSend successfull\n"; //Test send
	
	print_r($result);
	
	$soap->logout($session);
	
	//echo "logout successfull\n"; //Test log out
	}
	
	catch(SoapFault $fault)
	{
	echo $fault;
	}
}
?>