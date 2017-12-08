<?php
	error_reporting(0);
	header("Content-Type: text/html; charset=utf-8");

	require_once("data.php");

	$select = new select();
	$data = $select->dispositivoTipoXsistema($_POST["id"]);
	$dispositivo = array();

	while($fila = mysqli_fetch_assoc($data)){
		$id=$fila['DIS_ID'];
		$nombre=$fila['DIS_NOM'];
		$topico=$fila['DIS_TOP'];
		$estado=$fila['DIS_EST'];
		$color=$fila['DIS_COL'];
		$tipo=$fila['TIPO_NOM'];
		$habitacion=utf8_encode($fila['HAB_NOM']);
		$dispositivo[] = array('id'=> $id,  'nombre'=> $nombre, 'topico'=> $topico, 'estado'=> $estado,  'color'=> $color, 'tipo'=> $tipo,  'habitacion'=> $habitacion);
	}

	$json = json_encode($dispositivo, JSON_UNESCAPED_UNICODE);
	echo $json;
?>