<?php
	error_reporting(0);
	header("Content-Type: text/html; charset=utf-8");
	require_once("data.php");

	$select = new select();
	$data = $select->sistema($_POST[id]);
	$fila = mysqli_fetch_assoc($data);
	$sistema = array();

	$id=$fila['SIS_ID'];
	$nombre=$fila['SIS_NOM'];
	$ip=$fila["SIS_IP"];
	$puerto=$fila['SIS_PORT'];
	$path=$fila['SIS_PAT'];
	$usuario=$fila['SIS_USU'];
	$clave = $fila['SIS_PAS'];
	$ssl = $fila['SIS_SSL']? true : false;
	$keepalive = $fila['SIS_KEEP'];
	$timeout = $fila['SIS_OUT'];
	$cliente = $fila['SIS_CLID'];
	$clean = $fila['SIS_CLN']? true : false;
	$lastwilltopic = $fila['SIS_LWT'];
	$lastwillpayload = $fila['SIS_LWP'];
	$qos = $fila['SIS_QOS'];
	$retained = $fila['SIS_RET']? true : false;
	$url = $fila['SIS_URL'];
	$espera = $fila['SIS_COV'];
	$default = $fila['SIS_DEF']? true : false;
	$mail1 = $fila['SIS_MAIL1'];
	$mail2 = $fila['SIS_MAIL2'];

	$sistema[] = array('id'=> $id,  'nombre'=> $nombre, 'ip'=> $ip, 'puerto'=> $puerto, 'path'=> $path, 'usuario'=> $usuario, 'clave'=> $clave, 'ssl'=>$ssl, 'keepalive'=> $keepalive,  'timeout'=> $timeout, 'cliente'=> $cliente, 'clean'=> $clean, 'lastwilltopic'=> $lastwilltopic, 'lastwillpayload'=> $lastwillpayload, 'qos'=> $qos, 'retained'=> $retained, 'url'=>$url, 'espera'=>$espera, 'default'=>$default, 'mail1'=>$mail1, 'mail2'=>$mail2); 

	$json = json_encode($sistema);
	echo $json;
?>