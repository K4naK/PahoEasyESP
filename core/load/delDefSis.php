<?php
	require_once("../data.php");
	$id = $_POST["id"];
	$fun = $_POST["fun"];

	if($fun==0){
		$delete = new delete();
		$sistema = $delete->sistema($id);
		echo ($sistema>0)?"1":"0";
	}else if($fun==1){
		$update = new update();
		$sistema = $update->sistemaDefault($id);
		echo ($sistema>0)?"3":"2";
	}
?>