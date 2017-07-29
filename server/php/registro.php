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
			$_SESSION['error_1'] = 'Fallo en la autentificación';
			echo '<br>Fallo en la autentificación';
			return header('location: ../../index.php');
		}
		break;
	case 'newUser':
		$userName = $_POST['user'];
		$pssword1 = $_POST['pssword1'];
		$pssword2 = $_POST['pssword2'];
		$mail = $_POST['mail'];
		$answer = $user->newUser($userName, $pssword1, $pssword2, $mail);
		$_SESSION['error_2'] = $answer;
		return header('location: ../../index.php#newUser');
		break;
	case 'exit':
		$user->exit();
		$sys = new SYSTEM($_SESSION['path']);
		$sys->removeDir('../download');
		header('location: ../../');
		

}
//echo "name: $userName<br>password: $psswrd1<br>";





?>


