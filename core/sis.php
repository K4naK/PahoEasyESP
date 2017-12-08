<?php
	error_reporting(0);
	header("Content-Type: text/html; charset=utf-8");
	require_once("data.php");

	$select = new select();
	$data = $select->sistema("*");
	$sistema = array();

	while($fila = mysqli_fetch_assoc($data)){
		$id=$fila['SIS_ID'];
		$nombre=$fila['SIS_NOM'];
		$ip=$fila["SIS_IP"];
		$puerto=$fila['SIS_PORT'];
		$usuario=$fila['SIS_USU'];
		$clave = $fila['SIS_PAS'];
		$url = $fila['SIS_URL'];
		$espera = $fila['SIS_COV'];

		$sistema[] = array('id'=> $id,  'nombre'=> $nombre, 'ip'=> $ip, 'puerto'=> $puerto, 'usuario'=> $usuario, 'clave'=> $clave, 'url'=>$url, 'espera'=>$espera); 
	}
		
	$json = json_encode($sistema);
	echo $json;
?>