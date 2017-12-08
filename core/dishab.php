<?php
	error_reporting(0);
	header("Content-Type: text/html; charset=utf-8");

	require_once("data.php");

	$select = new select();
	$data = $select->dispositivoXHab($_POST["id"]);
	$dispositivo = array();

	while($fila = mysqli_fetch_assoc($data)){
		$id=$fila['DIS_ID'];
		$nombre=$fila['DIS_NOM'];
		$dispositivo[] = array('id'=> $id,  'nombre'=> $nombre);
	}

	$json = json_encode($dispositivo, JSON_UNESCAPED_UNICODE);
	echo $json;
?>