<?php
  require_once('config_aws.php');

  //Connect to mysql server
  $connection = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
  if(!$connection) {
	  die('Failed to connect to server: ' . mysql_error());
  }

  //Select database
  $db = mysql_select_db(DB_DATABASE, $connection);
  if(!$db) {
	  die("Unable to select database");
  }

?>
