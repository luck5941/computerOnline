<?php
include 'class.php';
$function = $_POST['function'];
$sys = new SYSTEM();

switch ($function) {
	case 'load':
		echo $sys->load();
		break;	
	default:		
		break;
}



?>
