<?php
define("HOST", "localhost");
define("USER_DB", "root");
define("PASSWD", "");
define("NAME_BD", "myDrive");

session_start();

if (!isset($_SESSION['path']))
	$_SESSION['path'] = '../..';

//$sql = mysqli_connect(HOST, USER_DB, PASSWD, NAME_BD);

class REGISTRO{
	public function login($user, $pssw){
		global $sql;
		$check =  mysqli_query($sql, "SELECT id_users from usuarios where user='$user'");
		$ids = [];
		while ($fila = mysqli_fetch_assoc($check)) {
			$ids[] = $fila['id_users'];
		}
		$check =  mysqli_query($sql, "SELECT id_users from usuarios where psswrd1='$pssw'");				
		while ($fila = mysqli_fetch_assoc($check)) {
			$ids[] = $fila['id_users'];
		}
		
		//return (isset($_SESSION['path'])) ? true: false;

		if (count($ids) > 1)
			return ($ids[0] === $ids[1]) ? true : false;
		else
			return false;    	
	}

	public function exit(){
		session_destroy();
		return true;
	}
}

/**
* 
*/
class SYSTEM{    
	private $path = '';
	private $main = '';
	private $pathToSee = '';
	private $zip = '';

	public function __construct($path) {
		$this->path = $path;
	}

	public function load($folder = ''){
			if ($folder == '')
				$this->path = $_SESSION['path'];
			else
				$this->path .= "/$folder";
			if ($this->path !== '../..'){
				$this->pathToSee = str_replace('../../', '', $this->path);
				$this->main = "<div class=\"element\" id=\"upLevel\"><div class=\"folderIcon\"><img src=\"server/img/upLevel.svg\"></div><div id=\"path\">$this->pathToSee</div></div>";
			}				
			//echo "this->path -> $this->path<br>";
			$files = scandir("$this->path");
			$_SESSION['path'] =  $this->path;
			//echo "last session -> ". $_SESSION['path'];
			foreach ($files as $key) {
				if ($key === '.' || $key === '..' || $key == '.git')
					continue;
				if(is_file("$this->path/$key")){
					$key = explode('.', $key);
					$this->main .=  "<div class=\"element\" id=\"$key[0]_$key[1]\"><div class=\"folderIcon\"><img src=\"server/img/file.svg\"><div class=\"name\">$key[0].$key[1]</div></div></div>";
				}
				elseif(is_dir("$this->path/$key"))
					$this->main .= "<div class=\"element\" id=\"$key\"><div class=\"folderIcon\"><img src=\"server/img/folder.svg\"><div class=\"name\">$key</div></div></div>";
				else 
					echo "<br>en el else: $this->path/$key";
						
			}
			return $this->main;
		
	}
	
	public function changeName($name) {
		echo "\nchangeName en  classPHP\nname=$name[0]\n";
		if (is_dir("$this->path/$name[0]") || is_file("$this->path/$name[0]")){
			echo "$this->path/$name[0], $this->path/$name[1]";
			echo (rename("$this->path/$name[0]", "$this->path/$name[1]")) ? "Se ha cambiado el nombre": "No se ha cambiado el nombre";
		}
		else{
			mkdir("$this->path/$name[0]", 0755);
			echo "creada la carpeta";
		}
	}

	public function upLevel(){
		$path = explode('/', $this->path);
		$tmp = '';
		for ($i = 0; $i< count($path)-2; $i++){
			$tmp .= $path[$i] . "/";
		}
		$tmp = substr($tmp, 0,-1);
		$this->path = $tmp;
		$_SESSION['path'] = $tmp;
		echo $this->load($path[count($path)-2]);
	}

	public function download($names) {
		if(!is_dir('../download')) mkdir('../download');
		if (count($_POST['names']) == 1 && is_file($this->path . "/" .$names[0])){
			$name = $names[0];
			copy($this->path . "/" .$names[0], "../download/download_." .explode('.', $name)[1]);
		}
		elseif (count($_POST['names']) == 1 && is_dir($this->path . "/" .$names[0])){
			$name = $this->compress([$this->path . "/" .$names[0]]);
		}
		else{
			$files = [];
			foreach ($names as $key ) {
				$files[] = "$this->path/$key";
			}
			$name = $this->compress($files);	
		}
		return (count($names) > 1) ? "<script>location.href=\"server/php/descarga.php?names=descarga.zip\";</script>" : "<script>location.href=\"server/php/descarga.php?names=".$name ."\";</script>";
					 

	}

	private function compress($path, $name=''){
		if (count($path) == 1){
			$this->zip = new zipArchive;
			$zipname = ($name == '') ? explode('/', $path[0])[count(explode('/', $path[0]))-1].'.zip' : $name;
			$this->zip->open($zipname, ZIPARCHIVE::CREATE);
			$this->addFile($path[0]);		
			$this->zip->close();
			rename($zipname, '../download/download_.zip');
			return $zipname;
		}
		
		elseif (count($path) > 1) {
			if (!is_dir('tmp')) mkdir('../tmp');
			foreach ($path as $file) {
				$this->copyFiles($file, '../tmp/');
			}
			$this->compress(['../tmp'], 'descarga.zip');
			$this->removeDir('../tmp');
		}
		
	}

	private function addFile($path, $currentpath = ''){
		$f = array_diff(scandir($path), ['.', '..']);
		foreach ($f as $file) {
			if (is_file("$path/$file"))
				$this->zip->addFile("$path/$file", "$currentpath$file");
			elseif (is_dir("$path/$file")){
				echo substr("$path/$file", 6);
				$this->zip->addEmptyDir("$currentpath$file");
				$this->addFile("$path/$file", "$currentpath$file" . "/");
			}
		}
	}

	private function copyFiles($source, $dest){
		$name = explode('/', $source);
		$name = $name[count($name)-1];
		//echo "<br>source->$source<br>dest->$dest<br>";
		if (is_file($source))
			copy($source, "$dest/$name");
		elseif (is_dir($source)) {
			mkdir("$dest/$name");
			$f = array_diff(scandir($source), ['.', '..']);
			foreach ($f as $key) {
				$this->copyFiles("$source/$key", "$dest/$name/");
			}
		}

	}

	public function removeDir($path){
		//echo "<br>path->$path<br>";
		if (is_file($path))
			unlink($path);
		elseif (is_dir($path)){
			$f = array_diff(scandir($path), ['.','..']);
			foreach ($f as $key ) {
				$this->removeDir("$path/$key");
			}
			rmdir($path);
		}
	}
	
	public function upLoad($files){
		for($i = 0; $i< count($files['files']['tmp_name']);$i++){
			if(move_uploaded_file($files['files']['tmp_name'][$i], $_SESSION['path']. "/". basename($files['files']['name'][$i]))){
				$name = explode('.', basename($files['files']['name'][$i]));
				echo "<div class=\"element\" id=\"$name[0]_$name[1]\"><div class=\"folderIcon\"><img src=\"server/img/file.svg\"><div class=\"name\">$name[0].$name[1]</div></div></div>";
			}
		}
	}

}



?>
