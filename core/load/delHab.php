<?php
	require_once("../data.php");

	$id = $_POST["id"];

	$delete = new delete();
	$habitacion = $delete->habitacion($id);
	echo ($habitacion>0)?"1":"0";
?>