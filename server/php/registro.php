<?php
include 'class.php';
$userName = $_POST['user'];
$psswrd1 = $_POST['pssword1'];
$user = new REGISTRO();
$function = $_POST['function'];
switch ($function){
	case 'login':
		return ($user->login($userName, $psswrd1))? header('location: ../../home.html') :  header('location: ../../index.html');
		break;
	case 'exit':
		$user->exit();
		$sys = new SYSTEM();
		$sys->removeDir('../download');
		header('location: ../../home.html');
		

}
echo "name: $userName<br>password: $psswrd1<br>";





?>
