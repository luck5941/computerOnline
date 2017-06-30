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
			//echo $_POST['name'] . " En proccess.php<br>";
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
		default:
			echo $function;
			break;
	}
}
elseif(isset($_FILES['files'])){
	echo $sys->upLoad($_FILES);
}



?>
