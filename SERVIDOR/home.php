<!DOCTYPE html>
<html>
<head>
	<title>Computer Online</title>
	<meta name="author" content="Lucas Elvira">
	<meta name="description" content="Free service to get access to one folder of your pc by internet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<link rel="icon" href="server/img/favicon.ico" type="image/x-icon"/>
	
	<?php
		include 'server/php/class.php';
		if (!isset($_SESSION['id'])) return header('location: index.php');
		$usr = new REGISTRO();
	?>
	<style type="text/css">
		<?=$usr->loadStyle();?>
	</style>
	<link rel="stylesheet" type="text/css" href="server/css/style.css">
</head>

<body id="home" onselectstart="return false">
	<div id=section>
		<header>
			<nav>
				<div class="option" id="descargar">
					<svg viewBox="0 0 100 100" preserveAspectRatio="none">
						<path class="line" d="M50 90 L80 50 L60 50 L60 10 L40 10 L40 50 L20 50 L50 90" rx=50/>
					</svg>
				</div>
				<div class="option" id="añadir">
					<svg viewBox="0 0 100 100" preserveAspectRatio="none">
						<circle r="40" cx="50" cy="50" class="line" />
						<line x1=25 y1=50 x2=75 y2=50 class="line" />
						<line y1=25 x1=50 y2=75 x2=50 class="line" />
					</svg>
				</div>
				<div class="option" id="subir">
					<label for="files">
						<svg viewBox="0 0 100 100" preserveAspectRatio="none">
							<g transform="rotate(180 50 50)">
								<path class="line" d="M50 90 L80 50 L60 50 L60 10 L40 10 L40 50 L20 50 L50 90" rx=50/>
							</g>
						</svg>
					</label>
				</div>
				<div class="option" id="setting">
					<svg viewBox="0 0 100 100" preserveAspectRatio="none">
						<g class="subline">
							<rect x="15" y="10" rx="5" ry="5" width="10" height="80" />
							<rect x="45" y="10" rx="5" ry="5" width="10" height="80" />
							<rect x="75" y="10" rx="5" ry="5" width="10" height="80" />
						</g>
						<g class=line>
							<rect x="5" y="45" rx="3" ry="3" width="30" height="10" />
							<rect x="35" y="20" rx="3" ry="3" width="30" height="10" />
							<rect x="65" y="65" rx="3" ry="3" width="30" height="10" />
						</g>
					</svg>
				</div>
				<div id="settingMenu">
					<nav>
						<li>Cambiar nombre usuario</li>
						<li>Cambiar contraseña</li>
						<li>Cambiar tema</li>
					</nav>
				</div>
			</nav>
		</header>
		<main id="lavel"></main>
	</div>
	<form action="server/php/registro.php" method="POST">
		<input name="function" type="submit" value="exit" />
	</form>
	<form id="fileupload" enctype="multipart/form-data" action="server/php/proccess.php" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
		<input type="file" id="files" name="files[]" multiple class="toCanvas" value="" />
		<input type="submit" value="Enviar fichero" id="submit" />
	</form>
	<div id=forms>
		<section>
			<h1 class="tittle">Cambio de nombre</h1>
			<div id="cambiarNombre" class="forms">
				<form action="server/php/proccess.php" method="POST">
					<p>Nombre viejo </p>
					<input class="input" type="password" name="user">
					<p>Nuevo nombre </p>
					<input class="input" type="password" name="newUser">
					<input style="display: none" name="function" value="changeNameUser">
					<div>
						<input type="submit" value="Confirmar">
					</div>
				</form>
			</div>
		</section>
		<section>
			<h1 class="tittle">Cambio de contraseña</h1>
			<div id="cambiarContraseña" class="forms">
				<form action="server/php/proccess.php" method="POST">
					<p>contraseña vieja </p>
					<input class="input" type="password" name="pssword">
					<p>Contraseña nueva </p>
					<input class="input" type="password" name="newUser">
					<p>Repite la contraseña </p>
					<input class="input" type="password" name="newUser2">
					<input style="display: none" name="function" value="changePssword">
					<div>
						<input type="submit" value="Confirmar">
					</div>
				</form>
			</div>
		</section>
		<section>
			<h1 class="tittle">Cambio de tema</h1>
			<article class="default">
				<h2>Default</h2>
				<div class="muestras">
					<svg viewBox="0 0 100 100" preserveAspectRatio="none">
						<circle cx=16 cy=50 r=16 />
						<circle cx=50 cy=50 r=16 />
						<circle cx=84 cy=50 r=16 />
					</svg>
				</div>
			</article>
			<article class="earth">
				<h2>Earth</h2>
				<div class="muestras">
					<svg viewBox="0 0 100 100" preserveAspectRatio="none">
						<circle cx=16 cy=50 r=16 />
						<circle cx=50 cy=50 r=16 />
						<circle cx=84 cy=50 r=16 />
					</svg>
				</div>
			</article>
			<article class="green">
				<h2>Green</h2>
				<div class="muestras">
					<svg viewBox="0 0 100 100" preserveAspectRatio="none">
						<circle cx=16 cy=50 r=16 />
						<circle cx=50 cy=50 r=16 />
						<circle cx=84 cy=50 r=16 />
					</svg>
				</div>
			</article>
		</section>
	</div>
	<div id=exit class="button">
		<svg viewBox="0 0 100 100" preserveAspectRatio="none">
			<circle cx="50" cy="50" r="45" class="line" />
			<line x1=30 y1=30 x2=70 y2=70 class="line" />
			<line y1=30 x1=70 y2=70 x2=30 class="line" />
		</svg>
	</div>

	<div id="search" class="button">
		<svg viewBox="0 0 100 100" preserveAspectRatio="none">
			<circle cx="50" cy="50" r="30" class="line" />
			<line x1=73  y1=73  x2=90 y2=90 class="line" />
		</svg>
	</div>

	<div id=ancla class="button">
		<svg viewBox="0 0 100 100" preserveAspectRatio="none">
			<circle cx="50" cy="50" r="45" class="line" />
			<path class="line" d="M25,40 a25,30 1 0,0 50,0" />
			<path class="line" d="M15 50 L25 40 L40 50" />
		</svg>
	</div>
	<div id="lift">
		<div class="liftButton">0</div>
		<div class="liftButton">-1</div>
		<div class="liftButton">-2</div>
		<div class="liftButton">-3</div>
	</div>
	<div id="progress">
		SUBIDO
		<svg viewBox="0 0 100 100" preserveAspectRatio="none">
<!-- <<<<<<< HEAD -->
			<defs>
					<linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
					<stop offset="0%" style="stop-color: #2B0000; stop-opacity: 1" />
					<stop offset="25%" style="stop-color: #9A2512; stop-opacity: 1" />
					<stop offset="50%" style="stop-color: #640052; stop-opacity: 1" />
					<stop offset="75%" style="stop-color: #2970C5; stop-opacity: 1" />
					<stop offset="100%" style="stop-color: #00C9CE; stop-opacity: 1" />
				</linearGradient>
			</defs>
			<rect x="5" y="1" width="90" height="7" fill="white" />
			<rect x="7.5" y="3" width="85" height="3" fill="none" stroke="var(--secondColor)" stroke-width="1" id="progressBarCont" />
			<rect x="8" y="3.75" width="84" height="1.5" fill="url(#grad1)" />
			<rect x="8" y="3.75" width="84" height="1.5" id="progressBar" fill="white" />
<!-- =======
			<rect x="5" y="1" width="90" height="15" fill="white" />
			<rect x="7.5" y="3.5" width="85" height="10" fill="none" stroke="var(--secondColor)" stroke-width="1" id="progressBarCont" />
			<rect x="8.5" y="4.5" width="83" height="8" id="progressBar" />
>>>>>>> 4273a8e4bd9c442689f2676b8c2eaab022e195a4 -->
		</svg>
	</div>
	<script type="text/javascript" src="server/js/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="server/js/main.js"></script>
</body>

</html>
