<?php session_start(); 
date_default_timezone_set("Asia/Bangkok");
session_destroy(); 
header("location:login.php");
exit;
?>
