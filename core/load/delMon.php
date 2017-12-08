<?php
	require_once("../data.php");

	$id = $_POST["id"];

	$delete = new delete();
	$monitor = $delete->monitor($id);
	echo ($monitor>0)?"1":"0";
?>