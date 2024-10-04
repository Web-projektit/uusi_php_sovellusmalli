<?php
$admin_mail = "etunimi.sukunimi@omnia.fi";
$smtpUsername = "username@gmail.com";
$smtpPassword = "password";

/* SendGrid */      
$password_sendgrid = "";    
$username_sendgrid = "";

/* Mailtrap */
$username_mailtrap = '';
$password_mailtrap = '';

$db_username_local = '';
$db_password_local = '';
$db_server_local = "127.0.0.1";
$site_local = "http://localhost";

if (strpos($_SERVER['HTTP_HOST'],"azurewebsites") !== false){
  $db_username_remote = '';
  $db_password_remote = '';
  $db_server_remote = "localhost:xxx";
  $site_remote = 'https://xxx.azurewebsites.net';
  }
?>