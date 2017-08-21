<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('mail.php');
define("HOST", "localhost");
define("USER_DB", "root");
define("PASSWD", "38973417elviralucas");
define("NAME_BD", "computerOnline");
session_start();
if (!isset($_SESSION['path']))
	$_SESSION['path'] = '../../../TRABAJOS/';

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
        $user = mysqli_real_escape_string($sql, $user);
		$check =  mysqli_query($sql, "SELECT id_users, turn from usuarios where mail='$user'");
		$ids = [];
		while ($fila = mysqli_fetch_assoc($check)) {
			$ids[] = $fila['id_users'];
			$turn = $fila['turn'];
		}
		$i = 0;
		$psswrd = $pssw;
		$this->turn = $this->uncryptNum($turn);
		for (;$i<=$this->turn; $i++){
			$psswrd = sha1(md5($psswrd));
		}
        $psswrd = mysqli_real_escape_string($sql, $psswrd);
		$check =  mysqli_query($sql, "SELECT id_users from usuarios where psswrd='$psswrd'");
		while ($fila = mysqli_fetch_assoc($check)) {
			$ids[] = $fila['id_users'];
		}
		 
		if (count($ids) > 1){
			$_SESSION['id'] = $ids[0];
			$_SESSION['turn'] = $turn;
			return ($ids[0] === $ids[1]) ? true : false;
		}
		else
			return false;
	}
    
	public function changePssword($oldPssword, $newPssword, $newPssword2){
		global $sql;
		if ($newPssword == $newPssword2){
			$this->turn = $this->uncryptNum($_SESSION['turn']);
			$psswrd = $oldPssword;
			for ($i=0;$i<=$this->turn; $i++){
				$psswrd = sha1(md5($psswrd));
			}
			$check =  mysqli_query($sql, "SELECT id_users from usuarios where psswrd='$psswrd'");				
			while ($fila = mysqli_fetch_assoc($check)) {
				$id = $fila['id_users'];
			}
			if (isset($id)){
				if ($id == $_SESSION['id']){
					$psswrdCryp = $this->crypPassword($newPssword);
					return (mysqli_query($sql, "UPDATE usuarios set psswrd='".$psswrdCryp['psswrd']."', turn = '".$psswrdCryp['bin']."' where id_users= $id")) ? "Se ha cambiado correctamente" : "Fallo en el proceso";
				}
				else
					return "Vaya, parece que la contraseña no coincide con la nuestra";
			}
			else
				return "Vaya, parece que la contraseña no coincide con la nuestra";

		}
		else
			return "las contraseñas no coinciden";
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
				$style = ":root{--firstColor: #bef9d8;--secondColor: #82d291;--thirdColor: #48b486;}";
				break;
		}
		return $style;
	}

	public function changeName($oldName, $newName){
		global $sql;
		$check = mysqli_query($sql, "SELECT id_users, turn from usuarios where users='$oldName'");
		while ($fila = mysqli_fetch_assoc($check)) {
			$id = $fila['id_users'];
		}
		if ($id == $_SESSION['id']){
			if (mysqli_query($sql, "UPDATE usuarios set users='$newName' where id_users= $id")){
				//rename("../img/$oldName", "../img/$newName");
				return "Proceso completado con exito";
			}
			else
				return "Ha habido un fallo en el proceso";
			}
		else
			return "Ha habido un fallo en el proceso";
	}

	public function changeTheme($theme) {
		$this->updateDatabase(['theme' => $theme]);
	}

	public function newUser($userName, $pssword1, $psswrd2, $mail){
		global $sql;
        if ($userName == '' || $pssword1 == '' || $psswrd2 == '' || $mail == '') return "Rellena todos los campos, por favor";
		if ($pssword1 !== $psswrd2) return "Las contraseñas no coinciden";
		$psswrdCryp = $this->crypPassword($pssword1);
        $userName = mysqli_real_escape_string($sql, $userName);
        $mail = mysqli_real_escape_string($sql, $mail);
        $query = "INSERT INTO usuarios (users, psswrd, mail, theme, turn) VALUES ('$userName', '".$psswrdCryp['psswrd']."', '$mail', 'default', '". $psswrdCryp['bin'] ."')";
		return  (mysqli_query($sql, $query)) ? "Registro con exito": "Vaya, parece que algo ha ido mal. ¿Puede que ya estes registrado?";
	}

	private function crypPassword($psswrd){
		$this->turn = mt_rand(0, pow(10, 6));
		$bin = $this->cryptNum($this->turn);
		$i = 0;
		$psswrd = $psswrd;
		for (;$i<=$this->turn; $i++){
			$psswrd = sha1(md5($psswrd));
		}
		return ['bin'=>$bin, 'psswrd'=>$psswrd];
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
		for ($i=0;$i<strlen($bin); $i++){
			if($i ==$tmp){
				$binDef .= "." . $str[$num];
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
    
    public function newPassword($name, $mail){
        global $sql;
        $name = mysqli_real_escape_string($sql, $name);
        $mail = mysqli_real_escape_string($sql, $mail);        
        $check =  mysqli_query($sql, "SELECT id_users from usuarios where mail='$mail' and users='$name'");
        if ($check){
            while($fila = mysqli_fetch_assoc($check)){
                $id = $fila['id_users'];
            }
            if (!isset($id)) {
            	$_SESSION['error_0'] = "Ups, parece que no te tenemos en nuestro sistema";
            	return header('location: ../../index.php#forget');
            }
            $password = substr(md5(microtime()), 0, 10);
            $psswrdCryp = $this->crypPassword($password);
            $update = mysqli_query($sql, "UPDATE usuarios set psswrd='".$psswrdCryp['psswrd']."', turn = '".$psswrdCryp['bin']."' where id_users= $id");
            if ($update){
                $sending = $this->sendMail($mail, $password, $name);
                $_SESSION['error_0'] = $sending ? "Se ha enviado" : "Fallo al enviar";
                return header('location: ../../index.php#forget');
            }
            else
                $_SESSION['error_0'] = "Fallo en el sistema, intentelo de nuevo más tarde"; 
        }
        else return "Vaya parece que no estás registrado";
    }
    
    private function sendMail($mailUser, $password, $name){
        global $mail;
        echo $name;
        $mail->setFrom($mailUser, "$name");
        $mail->AddAddress($mailUser, "$name");
        $mail->Subject = "Nueva contraseña";
        $content = file_get_contents('mailsTemplate/newPsswrd.html');
        $content = str_replace('$name', $name, $content);
        $content = str_replace('$password', $password, $content);
        $mail->msgHTML($content);
        return $mail->Send();
         
    }
}

class SYSTEM{    
	private $path = '';
	private $main = '';
	private $pathToSee = '';
	private $zip = '';
	private $find = [];

	public function __construct($path) {
		$this->path = $path;		
	}

	public function load($folder = ''){
			$pattern = '/(\.{2}\/){3}(TRABAJOS)\/*\w+/';
			if ($folder == '')
				$this->path = $_SESSION['path'];
			else{
				if (strpos('/', $folder) === false)
					$this->path= $this->path . "/$folder";
			}
			if (preg_match($pattern, $this->path)){
				$pattern = '/(\.{2}\/){3}(TRABAJOS)\/*/';
				$this->pathToSee = preg_replace($pattern, '', $this->path);
				//$this->pathToSee = preg_replace($pattern, '', $this->pathToSee);

				$this->main = "<div class=\"element\" id=\"upLevel\"><div class=\"folderIcon\"><svg viewBox=\"0 0 100 100\" preserveAspectRatio=\"none\"><polygon class=\"line\" points=\"23.7,0 0,26.5 15.7,26.5 15.7,86.9 84.2,86.9 84.2,58.3 68.1,58.3 68,70.8 31.8,70.8 31.9,26.6 47.6,26.6 \"/></svg></div><div id=\"path\">$this->pathToSee</div></div>";
			}				
			
			$files = scandir("$this->path");
			$_SESSION['path'] =  $this->path;
			
			foreach ($files as $key) {
				$coincide = preg_match('/\w*\.php|html|sql|server/', $key, $algo);
				if (strpos($key, '.') === 0|| $coincide)
					continue;
				if(is_file("$this->path/$key")){
					$key = explode('.', $key);
					$ext =  array_pop($key);
					$key = join('.', $key);
					$this->main .=  "<div class=\"element\" id=\"" . str_replace(".", "_", $key) . "_$ext\"><div class=\"folderIcon\"><img src=\"server/img/file.svg\"><div class=\"name\">$key.$ext</div></div></div>";
				}
				elseif(is_dir("$this->path/$key"))
					$this->main .= "<div class=\"element\" id=\"$key\"><div class=\"folderIcon\"><img src=\"server/img/folder.svg\"><div class=\"name\">$key</div></div></div>";				
						
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
		//$tmp = substr($tmp, 0,-1);
		$this->path = $tmp;
		$_SESSION['path'] = $tmp;
		echo $this->load($path[count($path)-2]);
	}

	public function download($names) {
		if(!is_dir('../download')) mkdir('../download');
		if (strpos($names[0], '/') !==false){
			$name = explode('/', $names[0]);
			$name = $name[count($name)-1];
			$names[0] =  (strpos($names[0], "undefined") !== false)? str_replace("undefined", $_SESSION['path'], $names[0]): "../..$names[0]";
			copy($names[0], "../download/download_." .explode('.', $name)[1]);
		}
		elseif (count($_POST['names']) == 1 && is_file($this->path . "/" .$names[0])){
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
		return (count($names) > 1) ? "<script>location.href=\"server/php/descarga.php?names=descarga.zip\";</script>" : "<script>location.href=\"server/php/descarga.php?names=".$name ."\"</script/>";
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

	private function findInside($str, $path){
		$files = array_diff(scandir($path), ['.', '..', '.git', 'server']);
		foreach ($files as $file) {
			if (strpos($file, $str) !== false){
				if (!isset($this->find[$path])){
					$this->find[$path] = [];					
				}
				$this->find[$path][] = $file;
			}
			if (is_dir("$path/$file"))
				//echo "is dir_ $path/$file";
				$this->findInside($str, "$path/$file");
		}
		
	}

	public function search($str){
		if (strpos($str, '/')!== false){
			$path = '../..';
			$str = substr($str, 1);
		}
		else
			$path = $_SESSION['path'];
		if ($str == '') return;
		$this->findInside($str, $path);
		//print_r($this->find);
		echo "<div class=\"group\">";
		$i = 0;
		foreach ($this->find as $key => $value) {
			foreach ($value as $subKey => $subValue) {
				// echo "\n$key contiente  $subValue\n";
				$i++;
				$icon = is_dir("$key/$subValue") ? "folder" : "file";
				echo "<div class=\"list\">
						<div class=\"icon\">
							<img  src=\"server/img/$icon.svg\">
						</div>
						<div class=\"name\">$subValue</div>
						<div class=\"path\">".substr($key, 5)."</div>
					</div>";
				if ($i%6 == 0)
					echo "</div><div class=\"group\">";
			}
		}
		echo "</div>";

	}

}


?>
