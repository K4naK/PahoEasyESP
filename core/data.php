<?php
	require_once("conexion.php");
	
	class select{
		
		private $con;
		
		function __construct() {
			$this->con = new conexion();
		}
		
		public function sistema($id){
			$sql = "SELECT * FROM SISTEMA ";
			if(empty($id)){
				$sql = $sql."WHERE SIS_DEF=1 ";
			}else if($id!="*"){
				$sql = $sql."WHERE SIS_ID=$id ";
			}
			$sql = $sql."ORDER BY SIS_DEF DESC";
			$resultado = mysqli_query($this->con->conexion,$sql);
			return $resultado;
		}
		
		public function habitacionXsistema($id){
			$sql = "SELECT *,(SELECT COUNT(*) FROM DISPOSITIVO WHERE DISPOSITIVO.HAB_ID=HABITACION.HAB_ID) AS CANT FROM HABITACION WHERE SIS_ID = $id ORDER BY HAB_NOM";
			$resultado = mysqli_query($this->con->conexion,$sql);
			return $resultado;
		}
		
		public function dispositivoTipoXsistema($id){
			$sql = "SELECT * FROM DISPOSITIVO INNER JOIN TIPO ON TIPO.TIPO_ID = DISPOSITIVO.TIPO_ID INNER JOIN HABITACION ON HABITACION.HAB_ID = DISPOSITIVO.HAB_ID WHERE SIS_ID=$id ORDER BY DIS_ID";
			$resultado = mysqli_query($this->con->conexion,$sql);
			return $resultado;
		}
		
		public function monitorXsistema($id){
			$sql = "SELECT * FROM MONITOR WHERE SIS_ID = $id";
			$resultado = mysqli_query($this->con->conexion,$sql);
			return $resultado;
		}
		
		public function dispositivoXid($id){
			$sql = "SELECT * FROM DISPOSITIVO INNER JOIN HABITACION ON HABITACION.HAB_ID = DISPOSITIVO.HAB_ID WHERE DIS_ID = $id";
			$resultado = mysqli_query($this->con->conexion,$sql);
			return $resultado;
		}
		
		public function dispositivoXHab($id){
			$sql = "SELECT * FROM DISPOSITIVO WHERE HAB_ID = $id";
			$resultado = mysqli_query($this->con->conexion,$sql);
			return $resultado;
		}
		
		public function logXdispositivo($id){
			$sql = "SELECT * FROM LOG WHERE DIS_ID=$id";
			$resultado = mysqli_query($this->con->conexion,$sql);
			return $resultado;
		}
		
		/*public function tipo(){
			$sql = "SELECT * FROM TIPO";
			$resultado = mysqli_query($this->con->conexion,$sql);
			return $resultado;
		}*/
	}
	
	class insert{
		
		private $con;
		
		function __construct() {
			$this->con = new conexion();
		}
		
		public function sistema($nom,$ip,$port,$path,$user,$pass,$ssl,$keep,$out,$client,$clean,$lwt,$lwp, $qos, $retain, $url, $cover){
			$sql = "SELECT * FROM SISTEMA WHERE SIS_DEF=1";
			$resultado = mysqli_query($this->con->conexion,$sql);
			if($fila = mysqli_fetch_assoc($resultado)){
				$default = 0;
			}else{
				$default = 1;
			}
			$sql = "INSERT INTO SISTEMA(SIS_ID, SIS_NOM, SIS_IP, SIS_PORT, SIS_PAT, SIS_USU, SIS_PAS, SIS_SSL, SIS_KEEP, SIS_OUT, SIS_CLID, SIS_CLN, SIS_LWT, SIS_LWP, SIS_QOS, SIS_RET, SIS_URL, SIS_COV, SIS_DEF, SIS_MAIL1, SIS_MAIL2) VALUES (NULL,'$nom','$ip',$port,'$path','$user','$pass',$ssl,$keep,$out,'$client',$clean,'$lwt','$lwp', $qos, $retain, '$url', $cover, $default, '$mail1', '$mail2')";
			mysqli_query($this->con->conexion,$sql);
			$resultado = mysqli_affected_rows($this->con->conexion);
			return $resultado;
		}
		
		public function habitacion($sistema,$nombre){
			$sql = "INSERT INTO HABITACION (HAB_ID, SIS_ID, HAB_NOM) VALUES (NULL, $sistema, '$nombre')";
			mysqli_query($this->con->conexion,$sql);
			$resultado = mysqli_affected_rows($this->con->conexion);
			return $resultado;
		}
		
		public function dispositivo($habitacion,$tipo,$nombre,$topico,$estado,$color){
			$sql = "INSERT INTO DISPOSITIVO (DIS_ID, HAB_ID, TIPO_ID, DIS_NOM, DIS_TOP, DIS_EST, DIS_COL) VALUES (NULL, $habitacion, $tipo, '$nombre', '$topico', '$estado', '$color')";
			mysqli_query($this->con->conexion,$sql);
			$resultado = mysqli_affected_rows($this->con->conexion);
			return $resultado;
		}
		
		public function monitor($sistema,$disIn,$valIn,$compare,$disOut,$valOut,$mail){
			$sql = "INSERT INTO MONITOR(MON_ID, SIS_ID, MON_DIS_IN, MON_VAL_IN, MON_COM, MON_DIS_OUT, MON_VAL_OUT, MON_MAIL) VALUES (NULL, $sistema, $disIn, '$valIn', '$compare', '$disOut', '$valOut', '$mail')";
			mysqli_query($this->con->conexion,$sql);
			$resultado = mysqli_affected_rows($this->con->conexion);
			return $resultado;
		}
	}

	class update{
		
		private $con;
		
		function __construct() {
			$this->con = new conexion();
		}
		
		public function sistema($id,$nom,$ip,$port,$path,$user,$pass,$ssl,$keep,$out,$client,$clean,$lwt,$lwp, $qos, $retain, $url, $cover, $mail1, $mail2){
			$sql = "UPDATE SISTEMA SET SIS_NOM='$nom',SIS_IP='$ip',SIS_PORT=$port,SIS_PAT='$path',SIS_USU='$user',SIS_PAS='$pass',SIS_SSL=$ssl,SIS_KEEP=$keep,SIS_OUT=$out,SIS_CLID='$client',SIS_CLN=$clean,SIS_LWT='$lwt',SIS_LWP='$lwp',SIS_QOS=$qos,SIS_RET=$retain,SIS_URL='$url',SIS_COV=$cover, SIS_MAIL1 = '$mail1', SIS_MAIL2 = '$mail2' WHERE SIS_ID=$id";
			mysqli_query($this->con->conexion,$sql);
			$resultado = mysqli_affected_rows($this->con->conexion);
			return $resultado;
		}
		
		public function sistemaDefault($id){
			$sql = "UPDATE SISTEMA SET SIS_DEF=0";
			mysqli_query($this->con->conexion,$sql);
			$sql = "UPDATE SISTEMA SET SIS_DEF=1 WHERE SIS_ID=$id";
			mysqli_query($this->con->conexion,$sql);
			$resultado = mysqli_affected_rows($this->con->conexion);
			return $resultado;
		}
		
		public function habitacion($id,$sistema,$nombre){
			$sql = "UPDATE HABITACION SET SIS_ID=$sistema, HAB_NOM='$nombre' WHERE HAB_ID = $id";
			mysqli_query($this->con->conexion,$sql);
			$resultado = mysqli_affected_rows($this->con->conexion);
			return $resultado;
		}
		
		public function dispositivo($id,$habitacion,$tipo,$nombre,$topico,$estado,$color){
			$sql = "UPDATE DISPOSITIVO SET HAB_ID=$habitacion,TIPO_ID=$tipo,DIS_NOM='$nombre',DIS_TOP='$topico',DIS_EST='$estado',DIS_COL='$color' WHERE DIS_ID = $id";
			mysqli_query($this->con->conexion,$sql);
			$resultado = mysqli_affected_rows($this->con->conexion);
			return $resultado;
		}

		public function monitor($id,$disIn,$valIn,$compare,$disOut,$valOut,$mail){
			$sql = "UPDATE monitor SET MON_DIS_IN='$disIn', MON_VAL_IN='$valIn', MON_COM='$compare',MON_DIS_OUT='$disOut',MON_VAL_OUT='$valOut',MON_MAIL=$mail WHERE MON_ID = $id";
			mysqli_query($this->con->conexion,$sql);
			$resultado = mysqli_affected_rows($this->con->conexion);
			return $resultado;
		}
	}

	class delete{
		
		private $con;
		
		function __construct() {
			$this->con = new conexion();
		}
		
		public function sistema($id){
			$sql = "DELETE FROM SISTEMA WHERE SIS_ID=$id";
			mysqli_query($this->con->conexion,$sql);
			$resultado = mysqli_affected_rows($this->con->conexion);
			return $resultado;
		}
		
		public function habitacion($id){
			$sql = "DELETE FROM HABITACION WHERE HAB_ID=$id";
			mysqli_query($this->con->conexion,$sql);
			$resultado = mysqli_affected_rows($this->con->conexion);
			return $resultado;
		}
		
		public function dispositivo($id){
			$sql = "DELETE FROM DISPOSITIVO WHERE DIS_ID=$id";
			mysqli_query($this->con->conexion,$sql);
			$resultado = mysqli_affected_rows($this->con->conexion);
			return $resultado;
		}
		
		public function monitor($id){
			$sql = "DELETE FROM MONITOR WHERE MON_ID=$id";
			mysqli_query($this->con->conexion,$sql);
			$resultado = mysqli_affected_rows($this->con->conexion);
			return $resultado;
		}
	}
?>