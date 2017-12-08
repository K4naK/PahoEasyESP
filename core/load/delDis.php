<?php
	require_once("../data.php");

	$id = $_POST["id"];

	$delete = new delete();
	$dispositivo = $delete->dispositivo($id);
	echo ($dispositivo>0)?"1":"0";
?>