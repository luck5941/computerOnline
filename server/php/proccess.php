<?php
include 'class.php';
$function = $_POST['function'];
echo "<br>free Sesion path-> " . $_SESSION['path']."<br>";
$sys = new SYSTEM($_SESSION['path']);


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
	default:
		echo $function;
		break;
}



?>
