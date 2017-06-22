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

	public function __construct($path) {
		$this->path = $path;
	}

	public function load($folder = ''){
		/*
			if ($this->path === '../..')
				$this->path = "../../$folder";
			
			else{
				$this->path .= "/$folder";
			}
		*/
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
		if (count($_POST['names']) < 1) return;
		 

	}



}



?>
