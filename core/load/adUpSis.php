<?php
	require_once("../data.php");
	$id = $_POST["id"];
	$nom = $_POST["nombre"];
	$ip = $_POST["ip"];
	$port = $_POST["puerto"];
	$path = $_POST["ruta"];
	$user = $_POST["usuario"];
	$pass = $_POST["clave"];
	$ssl = (isset($_POST["ssl"]) || !empty($_POST["ssl"]))?1:0;
	$keep = $_POST["keepalive"];
	$out = $_POST["timeout"];
	$client = $_POST["cliente"];
	$clean = (isset($_POST["limpia"]) || !empty($_POST["limpia"]))?1:0;
	$lwt = $_POST["lwt"];
	$lwp = $_POST["lwp"];
	$qos = $_POST["qos"];
	$retain = (isset($_POST["retain"]) || !empty($_POST["retain"]))?1:0;
	$url = $_POST["url"];
	$cover = $_POST["cover"];
	$mail1 = $_POST["mail1"];
	$mail2 = $_POST["mail2"];

	if($id==0){
		$insert = new insert();
		$sistema = $insert->sistema($nom,$ip,$port,$path,$user,$pass,$ssl,$keep,$out,$client,$clean,$lwt,$lwp, $qos, $retain, $url, $cover,$mail1,$mail2);
		echo ($sistema>0)?"1":"0";
	}else if($id>0){
		$update = new update();
		$sistema = $update->sistema($id,$nom,$ip,$port,$path,$user,$pass,$ssl,$keep,$out,$client,$clean,$lwt,$lwp, $qos, $retain, $url, $cover, $mail1, $mail2);
		echo ($sistema>0)?"2":"0";
	}
?>