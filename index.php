<?php
  require_once('auth.php');
  auth();
  header("location: dashboard.php");
?>