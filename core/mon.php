<?php
	//error_reporting(0);
	header("Content-Type: text/html; charset=utf-8");

	require_once("data.php");
	$select = new select();
	$data = $select->monitorXsistema($_POST["id"]);
	$monitor = array();

	while($fila = mysqli_fetch_assoc($data)){
		
		$idMon = $fila["MON_ID"];
		$iddisIn = $fila['MON_DIS_IN'];
		$valIn = $fila['MON_VAL_IN'];
		$compare = $fila['MON_COM'];
		$iddisOut = $fila['MON_DIS_OUT'];
		$valOut = $fila['MON_VAL_OUT'];
		$mail = $fila['MON_MAIL']? true : false;
		
		$idHabIn = "";
		$nomHabIn = "";
		$nomDisIn = "";
		$topIn = "";
		$idHabOut = "";
		$nomHabOut = "";
		$nomDisOut = "";
		$topOut = "";
		
		$dispositivo = $select->dispositivoXid($iddisIn);
		$row = mysqli_fetch_assoc($dispositivo);
		
		$idHabIn = $row["HAB_ID"];
		$nomHabIn = $row["HAB_NOM"];
		$nomDisIn = $row["DIS_NOM"];
		$topIn = $row["DIS_TOP"];
		
		if($iddisOut){
			$dispositivo = $select->dispositivoXid($iddisOut);
			$row = mysqli_fetch_assoc($dispositivo);
		
			$idHabOut = $row["HAB_ID"];
			$nomHabOut = $row["HAB_NOM"];
			$nomDisOut = $row["DIS_NOM"];
			$topOut = $row["DIS_TOP"];
		}
		
		$habitacion[] = array('idMon'=>$idMon, 'idDisIn'=>$iddisIn, 'valIn'=>$valIn, 'compare'=>$compare, 'idDisOut'=>$iddisOut, 'valOut'=>$valOut, 'mail'=>$mail, 'idHabIn'=>$idHabIn, 'nomHabIn'=>$nomHabIn, 'nomDisIn'=>$nomDisIn, 'topIn'=>$topIn, 'idHabOut'=>$idHabOut, 'nomHabOut'=>$nomHabOut, 'nomDisOut'=>$nomDisOut, 'topOut'=>$topOut);
	}

	$json = json_encode($habitacion, JSON_UNESCAPED_UNICODE);
	echo $json;
?>