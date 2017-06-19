<?php
include 'class.php';
$userName = $_POST['user'];
$psswrd1 = $_POST['pssword1'];
$user = new REGISTRO();
echo "name: $userName<br>password: $psswrd1<br>";
return ($user->login($userName, $psswrd1))? header('location: ../../home.html') :  header('location: ../../index.html');




?>
