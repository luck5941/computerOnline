<?php
session_start();
$name=$_GET['name'];
$path = $_SESSION['path'];
header("Content-disposition: attachment; filename=$name");
header("Content-type: application/pdf");
readfile("../../server/download/download_");

?>