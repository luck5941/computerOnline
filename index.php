<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>.</title>
    <meta charset="utf-8">
    <style type="text/css">
         :root {
            --firstColor: #DFFCFF;
            --secondColor: #00C9CE;
            --thirdColor: #80c1c3;
        }

    </style>
    <link rel="stylesheet" type="text/css" href="server/css/style.css">
</head>

<body id="index">
    <section name="psswrd">
        <div class="forms">
            <div class="init">Cambio de contraseña</div>
            <form action="server/php/proccess.php" method="POST">
                <p>Nombre de usuario: </p><input class="input" type="text" name="user">
                <p>correo: </p><input class="input" type="email" name="mail">
                <input style="display: none" name="function" value="forgotPsswrd">
                <div><input type="submit" value="Enviar"></div>
            </form>
            <div class=error>
                <?=(isset($_SESSION['error_0']) ? $_SESSION['error_0'] : '')?>
            </div>
            <div class=links index="0">¿Se te ha olvidado la contraseña?</div>
            <div class=links index="1">Inicio de sesión</div>
            <div class=links index="2">¿No tienes cuenta?</div>
        </div>
    </section>
    <section>
        <div id="login" class="forms">
            <div class="init">Tienes que inicar sesión para continuar</div>
            <form action="server/php/registro.php" method="POST">
                <p>Correo electronico: </p><input class="input" type="text" name="user">
                <p>Contraseña: </p><input class="input" type="password" name="pssword1">
                <input style="display: none" name="function" value="login">
                <div><input type="submit" value="Enviar"></div>
            </form>
            <div class=error>
                <?=(isset($_SESSION['error_1']) ? $_SESSION['error_1'] : '')?>
            </div>
            <div class=links index="0">¿Se te ha olvidado la contraseña?</div>
            <div class=links index="1">Inicio de sesión</div>
            <div class=links index="2">¿No tienes cuenta?</div>
        </div>
    </section>

    <section>
        <div class="forms">
            <div class="init">Registro</div>
            <form action="server/php/registro.php" method="POST">
                <p>Nombre de usuario: </p><input class="input" type="password" name="user">
                <p>Mail: </p><input class="input" type="email" name="mail">
                <p>Contraseña: </p><input class="input" type="password" name="pssword1">
                <p>Vuelve a introducir la contraseña: </p><input class="input" type="password" name="pssword2">
                <input style="display: none" name="function" value="newUser">
                <div><input type="submit" value="Enviar"></div>
            </form>

            <div class=error>
                <?= (isset($_SESSION['error_2']) ? $_SESSION['error_2'] : '')?>
            </div>
            <div class=links index="0">¿Se te ha olvidado la contraseña?</div>
            <div class=links index="1">Inicio de sesión</div>
            <div class=links index="2">¿No tienes cuenta?</div>
        </div>
    </section>

    <script type="text/javascript" src="server/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="server/js/main.js"></script>
    <script type="text/javascript" src="server/js/index.js"></script>

</body>

</html>
