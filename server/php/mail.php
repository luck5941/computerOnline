<?php 
date_default_timezone_set('Etc/UTC');
require 'mail/PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
$mail->CharSet = 'UTF-8';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = "campusvirtual111@gmail.com";
$mail->Password = "proyecto_Campus";
$mail->from = "campusvirtual111@gmail.com";
$mail->fromName = "Online computer";

?>
