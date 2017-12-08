<?php
	require_once("../data.php");
	$id = $_POST["id"];
	$nombre = $_POST["nombre"];
	$tipo = $_POST["tipo"];
	$habitacion = $_POST["habitacion"];
	$color = $_POST["color"];
	$topico = $_POST["topico"];
	$estado = $_POST["estado"];

	if($id==0){
		$insert = new insert();
		$dispositivo = $insert->dispositivo($habitacion,$tipo,$nombre,$topico,$estado,$color);
		echo ($dispositivo>0)?"1":"0";
	}else if($id>0){
		$update = new update();
		$dispositivo = $update->dispositivo($id,$habitacion,$tipo,$nombre,$topico,$estado,$color);
		echo ($dispositivo>0)?"2":"0";
	}
?>