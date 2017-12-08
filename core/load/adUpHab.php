<?php
	require_once("../data.php");
	$id = $_POST["id"];
	$sistema = $_POST["sistema"];
	$nombre = $_POST["nombre"];

	if($id==0){
		$insert = new insert();
		$habitacion = $insert->habitacion($sistema,$nombre);
		echo ($habitacion>0)?"1":"0";
	}else if($id>0){
		$update = new update();
		$habitacion = $update->habitacion($id,$sistema,$nombre);
		echo ($habitacion>0)?"2":"0";
	}
?>