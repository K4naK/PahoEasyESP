<?php
	require_once("../data.php");
	$id = $_POST["id"];
	$sistema = $_POST["sistema"];
	$disIn = $_POST["dispositivoIn"];
	$valIn = $_POST["valIn"];
	$compara = $_POST["compara"];
	$disOut = isset($_POST["dispositivoOut"])?$_POST["dispositivoOut"]:"";
	$valOut = $_POST["valOut"];
	$mail = (isset($_POST["mail"]) || !empty($_POST["mail"]))?1:0;
	if($id==0){
		$insert = new insert();
		$dispositivo = $insert->monitor($sistema,$disIn,$valIn,$compara,$disOut,$valOut,$mail);
		echo ($dispositivo>0)?"1":"0";
	}else if($id>0){
		$update = new update();
		$monitor = $update->monitor($id,$disIn,$valIn,$compara,$disOut,$valOut,$mail);
		echo ($monitor>0)?"2":"0";
	}
?>