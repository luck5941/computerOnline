OnlineComputer
===
### Descripción:

Este proyecto consiste en la creación de un servicio para poder acceder de forma remota a tus archivos de la carpeta escogida de tu ordenador, pudiendo crear tus propias subcarpetas o subiendo archivos. Lo que no se deja es borrar carpetas por miedo a que un usuario que no sea el dueño del servicio pueda borrar los archivos del usuario legitimo 

### Versión:

En esta parte nos encontramos en la version 2.0. En la primera nos tenía interfaz gráfica o era muy simple. Además se han implementado nuevos protocolos de seguridad.

### Tecnología


##### Front-end

La tecnologia base de este proyecto es html y css, incluyendo imágenes SVG para permitir una mayor personalización por parte del usuario final. Además, toda la interacción posible y parte de la comunicación con el servidor está realizada en js simple, con ayuda de jquery.

presenta un diseño ***one-page*** con posibilidad de una personalización del estilo sin necesidad  de tocar el código, a menos que se quiera llegar a una personalización más exclusiva

##### Back-end

En esta versión el back-end se ha realizado en PHP y MYSQL, con un soporte del servidor XAMPP.
La versión de PHP empleada es la 7.0
El único modulo que se ha necesitado instalar es php7.0-zip para la compresión de archivos y phpmailer con la finalidad de poder enviar mails de forma automatica

La configuración para el envío de los mails se encuentra en /server/php/mail.php y está pensada para usar un cliente de correos de *GMAIL*, sin incluir la cuenta de correo ni la contraseña de la misma.

La base de datos se puede llamar como se quira ya que se define el nombre de la misma como constante en /server/php/class.php. Y la estructura VACIA se encuetra en /server/sql/computerOnline.sql

### Licencia


Se ha realizado bajo los terminos de [LGPL-3.0](https://opensource.org/licenses/LGPL-3.0). Por favor, leer maś acerca de ella para conocer las posibilidades de uso y distribución

### Resumen


La estructura de carpetas esta formada de la siguiente manera:
```
SERVIDOR
    |index.php -> formulario de registro, inicio de sesión y recuperación de contraseñas
	|home.php -> página con la que interactuar con la aplicación en cuestion
	|server -> carpeta con toda la lógica
		|css -> hojas de estilo
			|css -> style.css
		|img -> carpeta con las imágenes que emplea la aplicación
			|file.svg
			|folder.svg
		|js -> carpeta con la lógica del front - end
			|jquery-3.1.1.min.js
			|main.js -> La lógica principal de la aplicación
			|index.js -> archivo exclusivo para index.php
		|php -> carpeta con la lógica del back-end
			|class.php -> Todos los métodos del back end
			|descarga.php -> archivo encargado de las descargas de archivos
			|mail.php -> configuración del servicio de mail
			|registro.php -> script encargado de procesar nuevos usuarios, registrar los nuevos o cerrar sesión
			|mail -> carpeta con los archivos de phpmailer
			|mailsTemplate -> carpeta con las plantillas para los mails
			    |newPsswrd.html
		|sql -> carpeta contenedora de la base de datos vacia
|CARPETA CON LOS ARCHIVOS, EN MI CASO TRABAJOS
```
