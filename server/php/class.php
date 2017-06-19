<?php
define("HOST", "localhost");
define("USER_DB", "root");
define("PASSWD", "");
define("NAME_BD", "myDrive");

$sql = mysqli_connect(HOST, USER_DB, PASSWD, NAME_BD);

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
}

/**
* 
*/
class SYSTEM{    
    public $path = '../../';    
    public function load(){
        $files = scandir($this->path);
        foreach ($files as $key) {
            if ($key == '.' || $key == '..')
                continue;
			$key = explode('.', $key);
            echo "<div class=\"element\" id=\"$key[0]\"><div class=\"folderIcon\"><img src=\"server/img/folder.svg\"><div class=\"name\">$key[0]</div></div></div>";
        }    
    }
}



?>
