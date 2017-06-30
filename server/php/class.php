<?php
define("HOST", "localhost");
define("USER_DB", "root");
define("PASSWD", "");
define("NAME_BD", "myDrive");

session_start();

if (!isset($_SESSION['path']))
	$_SESSION['path'] = '../..';

$sql = mysqli_connect(HOST, USER_DB, PASSWD, NAME_BD);

class REGISTRO{
	private $id;
	private $turn;
	private $str = "abcC7EU/yQ,3o9L1WYrmfRK4VM-BA0nx;jDlHPSh*X+J\pgGkd6IzZN2TÑtvswqFOeu:i85";
	public function __construct() {
		$this->id = (isset($_SESSION['id'])) ? $_SESSION['id'] : '';
	}

	public function login($user, $pssw){
		global $sql;
		$check =  mysqli_query($sql, "SELECT id_users, turn from usuarios where users='$user'");
		$ids = [];
		while ($fila = mysqli_fetch_assoc($check)) {
			$ids[] = $fila['id_users'];
			$turn = $fila['turn'];
		}
		echo $turn; 
		$i = 0;
		$psswrd = $pssw;
		$this->turn = $this->uncryptNum($turn);
		echo "<br>" . $this->turn . "<br>"; 
		for (;$i<=$this->turn; $i++){
			$psswrd = sha1(md5($psswrd));
		}
		$check =  mysqli_query($sql, "SELECT id_users from usuarios where psswrd='$psswrd'");				
		while ($fila = mysqli_fetch_assoc($check)) {
			$ids[] = $fila['id_users'];
		}
		echo count($ids); 
		//return (isset($_SESSION['path'])) ? true: false;
		
		if (count($ids) > 1){
			$_SESSION['id'] = $ids[0];
			return ($ids[0] === $ids[1]) ? true : false;
		}
		else
			return false;    
			
	}

	public function loadStyle() {
		global $sql;
		$check =  mysqli_query($sql, "SELECT theme from usuarios where id_users='$this->id'");
		while ($fila = mysqli_fetch_assoc($check)) {
			$theme = $fila['theme'];
		}
		switch ($theme) {
			case 'default':
				$style = ":root{--firstColor: #DFFCFF;--secondColor: #00C9CE;--thirdColor: #80c1c3;}";
				break;
			case 'earth':
				$style = ":root{--firstColor: #f2eaca;--secondColor: #f2db82;--thirdColor: #c67a09;}";
				break;
			case 'green':
				$style = ":root{--firstColor: #bef9d8;--secondColor: #82f297;--thirdColor: #48b486;}";
				break;
		}
		return $style;
	}


	public function changeTheme($theme) {
		$this->updateDatabase(['theme' => $theme]);
	}

	public function newUser($userName, $pssword1, $psswrd2, $mail){
		global $sql;
		if ($userName == '' || $pssword1 == '' || $psswrd2 == '' || $mail == '') return "Rellena todos los campos, por favor";
		if ($pssword1 !== $psswrd2) return "Las contraseñas no coinciden";
		$this->turn = mt_rand(0, pow(10, 6));
		$bin = $this->cryptNum($this->turn);
		$i = 0;
		$psswrd = $pssword1;
		for (;$i<=$this->turn; $i++){
			$psswrd = sha1(md5($psswrd));
		}
		echo "<br>INSERT INTO usuarios (users, psswrd, mail, theme, turn) VALUES ('$userName', '$psswrd', '$mail', 'default', '$bin')<br>";
		return  (mysqli_query($sql, "INSERT INTO usuarios (users, psswrd, mail, theme, turn) VALUES ('$userName', '$psswrd', '$mail', 'default', '$bin')")) ? "Registro con exito": "Vaya algo ha ido mal";
		echo "<br>psswrd-> $psswrd<br>bin-> $bin<br>turn-> $this->turn";
	}

	private function cryptNum($num){
		$str = $this->str;
		$bin = '';
		$binDef = '';
		$len = strlen($str);
		while ($num>=$len) {
			$tmp = intval($num % $len);
			$bin .= $str[$tmp];
			$num =  intval($num/$len);
		}
		$tmp = mt_rand(0, strlen($bin)-1);
		echo $tmp;
		for ($i=0;$i<strlen($bin); $i++){
			if($i ==$tmp){
				$binDef .= "." . $str[$num];
				echo "<br>Coincide<br>";
			}
			$binDef .= $bin[$i];
		}
		return $binDef;
	}

	private function uncryptNum($str){
		$arr = explode('.', $str);
		$arr[0] .= substr($arr[1], 1);
		$i = strlen($arr[0])-1;
		$mult = strlen($this->str);
		$tmp = 0;
		for (; $i>=0; $i--){
			$tmp = ($tmp == 0) ? ($mult*strpos($this->str, $arr[1][0])+strpos($this->str, $arr[0][$i])) : ($mult*$tmp+strpos($this->str, $arr[0][$i]));
		}
		return $tmp;
	}

	/*
		private function findNum($str, $chr){
			for ($i =0; $i<strlen($str); $i++){
				if ($str[$i] === $chr)
					return $i;
			}
		}
		private function cryptNum($num){
			$tmpBin = '';
			$bin = '';
			$i;
			while ($num>=1){
				$tmpBin .= strval($num%2);
				$num = $num/2;
			}
			echo "tmpBin-> $tmpBin<br>";
			$i = strlen($tmpBin)-1;
			echo "i vale: $i<br>";
			while ( $i>= 0) {
				$bin .= $tmpBin[$i];
				$i--;
			 }


			return $bin;
		}

		private function uncryptNum($num){
			$power = 0;
			$decimal = 0;
			while ($num != 0) {
				$lstDigit = $num%10;
				$decimal += $lstDigit * pow(2, $power);
				$power++;
				$num = $num/10;
			}
			return $decimal;
		}
	*/
	private function updateDatabase ($values){
		global $sql;
		foreach ($values as $key => $value) {
			mysqli_query($sql, "update usuarios set $key='$value' where id_users = $this->id");
		}

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
