<?php
include 'class.php';
$user = new REGISTRO();		
$function = $_POST['function'];

switch ($function){
	case 'login':
		$userName = $_POST['user'];
		$psswrd1 = $_POST['pssword1'];
		if ($user->login($userName, $psswrd1))
			return header('location: ../../home.php');
		else{
			session_destroy();
			return header('location: ../../index.html');
		}
		break;
	case 'exit':
		$user->exit();
		$sys = new SYSTEM($_SESSION['path']);
		$sys->removeDir('../download');
		header('location: ../../index.html');
		

}
//echo "name: $userName<br>password: $psswrd1<br>";





?>
