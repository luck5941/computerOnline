<?php
if (isset($_SESSION)) {return header('location: index.html');}
?>

<!DOCTYPE html>
<html>
<head>
	<title>My drive</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="server/css/style.css">
</head>
<!--<body id="home" onselectstart="return false">-->
<body id="home">
<header>
		<nav>
			<div class="option" id="comprimir">
				<img src="server/img/comprimir.svg">
			</div>
			<div class="option" id="descargar">
				<img src="server/img/download.svg">
			</div>
			<div class="option" id="añadir">			
				<svg viewBox="0 0 100 100" preserveAspectRatio="none">
					<circle r="40" cx="50" cy="50" class="line" />
					<line x1=25 y1=50 x2=75 y2=50 class="line" />
					<line y1=25 x1=50 y2=75 x2=50 class="line" />
				</svg>			
			</div>
			<div class="option" id="subir">			
				<img src="server/img/subir.svg">			
			</div>
		</nav>
</header>

<main id="lavel">	
	<div class="element">
		<div class="folderIcon">
			<img src="server/img/folder.svg">
			<div class="name">Carpeta 1</div>
		</div>
	</div>
	<div class="element">
		<div class="folderIcon">
			<img src="server/img/folder.svg">
			<div class="name">Carpeta 2</div>
		</div>
	</div>
	<div class="element">
		<div class="folderIcon">
			<img src="server/img/folder.svg">
			<div class="name">Carpeta 3</div>
		</div>
	</div>
	<div class="element">
		<div class="folderIcon">
			<img src="server/img/folder.svg">
			<div class="name">Carpeta 1</div>
		</div>
	</div>
	<div class="element">
		<div class="folderIcon">
			<img src="server/img/folder.svg">
			<div class="name">Carpeta 2</div>
		</div>
	</div>
	<div class="element">
		<div class="folderIcon">
			<img src="server/img/folder.svg">
			<div class="name">Carpeta 3</div>
		</div>
	</div>
	<div class="element">
		<div class="folderIcon">
			<img src="server/img/folder.svg">
			<div class="name">Carpeta 1</div>
		</div>
	</div>
	<div class="element">
		<div class="folderIcon">
			<img src="server/img/folder.svg">
			<div class="name">Carpeta 2</div>
		</div>
	</div>
	<div class="element">
		<div class="folderIcon">
			<img src="server/img/folder.svg">
			<div class="name">Carpeta 3</div>
		</div>
	</div>
	
</main>
<form action="server/php/registro.php" method="POST"><input name="function" type="submit" value="exit"/></form>
<div id=exit>
	<svg viewBox="0 0 100 100" preserveAspectRatio="none">
		<circle cx="50" cy="50" r="40" class="line"/>
		<line x1=30 y1=30 x2=70 y2=70 class="line" />
		<line y1=30 x1=70 y2=70 x2=30 class="line" />
	</svg>
</div>
<script type="text/javascript" src="server/js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="server/js/main.js"></script>



</body>
</html>
