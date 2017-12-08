<?php
	error_reporting(0);
	header("Content-Type: text/html; charset=utf-8");

	require_once("data.php");

	$select = new select();
	$data = $select->dispositivoXid($_POST["id"]);
	$dispositivo = array();

	$fila = mysqli_fetch_assoc($data);
	$id=$fila['DIS_ID'];
	$nombre=$fila['DIS_NOM'];
	$topico=$fila['DIS_TOP'];
	$estado=$fila['DIS_EST'];
	$color=$fila['DIS_COL'];
	$tipo=$fila['TIPO_ID'];
	$habitacion=$fila['HAB_NOM'];
	$habitacionId=$fila['HAB_ID'];
	$dispositivo[] = array('id'=> $id,  'nombre'=> $nombre, 'topico'=> $topico, 'estado'=> $estado,  'color'=> $color, 'tipo'=> $tipo, 'habitacion'=> $habitacion, 'habitacionId'=> $habitacionId);
	

	$json = json_encode($dispositivo);
	echo $json;
?>