<?php
include 'class.php';
$sys = new SYSTEM($_SESSION['path']);
$user = new REGISTRO();
if (isset($_POST['function'])){
	$function = $_POST['function'];
	switch ($function) {
		case 'load':
			echo $sys->load();			
			break;
		case 'changeName':
			echo $sys->changeName($_POST['name']);
			break;
		case 'openDirectory':			
			echo $sys->load($_POST['name']);
			break;
		case 'upLevel':
			echo $sys->upLevel();
			break;
		case 'download':
			echo $sys->download($_POST['names']);
			break;
		case 'changeTheme':
			$user->changeTheme($_POST['theme']);
			break;
		case 'changeNameUser':
			echo $user->changeName($_POST['user'], $_POST['newUser']);
			break;
		case 'changePssword':
			echo $user->changePssword($_POST['pssword'], $_POST['newUser'], $_POST['newUser2']);
			break;
		case 'search':
			$sys->search($_POST['val']);
			break;
        case 'forgotPsswrd':
            $user->newPassword($_POST['user'], $_POST['mail']);
            break;
		default:
			echo "$function <- default";
			break;
	}
}
elseif(isset($_FILES['files'])){
	echo $sys->upLoad($_FILES);
}



?>
