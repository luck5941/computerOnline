<?php
session_start();
$name=$_GET['names'];
$ext = explode('.', $name)[1];
header("Content-disposition: attachment; filename=$name");
header("Content-type: " . mime_content_type("../download/download_.$ext"));
readfile("../download/download_.$ext");

?>