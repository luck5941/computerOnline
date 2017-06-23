<?php
session_start();
$name=$_GET['names'];
$ext = explode('.', $name)[1];
header("Content-disposition: attachment; filename=$name");
//header("Content-type: application/pdf");
readfile("../download/download_.$ext");

?>