<?php
	error_reporting(0);
	header("Content-Type: text/html; charset=utf-8");

	require_once("data.php");
	$select = new select();
	$data = $select->habitacionXsistema($_POST["id"]);
	$habitacion = array();

	while($fila = mysqli_fetch_assoc($data)){
		$id = $fila['HAB_ID'];
		$nombre = utf8_encode($fila['HAB_NOM']);
		$cant = $fila['CANT'];
		$habitacion[] = array('id'=> $id, 'nombre'=> $nombre, 'cantidad'=> $cant);
	}

	$json = json_encode($habitacion, JSON_UNESCAPED_UNICODE);
	echo $json;
?>