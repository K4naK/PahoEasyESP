<?php


	$subject = "Correo de Verificacion - InfoArica";
	$message = "
	<html>
		<head>
			<title>Correo de Verificacion - InfoArica</title>
		</head>
		<body>
			<b>
			Estimado:<br>
			Se ha creado una cuenta en el sitio <a href='www.infoarica.aricati.cl'>InfoArica</a><br>
			Para activar esta cuenta haga click en el siguiente link 
			<a href='http://infoarica.aricati.cl/'>ACTIVAR</a>
			</b>
		</body>
	</html>
	";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8"."\r\n";
	$headers .= 'From: InfoArica <noresponder@infoarica.cl>'."\r\n";
	mail('janox.003@gmail.com',$subject,$message,$headers);
?>