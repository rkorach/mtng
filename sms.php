<?php
function sendSMS($to,$message){
	//Seesion OVH
	$nic="xx123456-ovh";
	$pass="ovh123456";
	$sms_compte="sms-xx123456-1";
	
	//Our OVH phone number
	$from="+XX";
	
	try
	{
	$soap = new SoapClient("https://www.ovh.com/soapi/soapi-re-1.8.wsdl");
	$session = $soap->login("$nic", "$pass","fr", false);
	
	//echo "login successfull\n"; //Test connexion
	
	$result = $soap->telephonySmsSend($session, "$sms_compte", "$from", "$to", "$message", "", "1", "", "");
	
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