<?php

class conexion {

    public $host = "localhost";
	public $user = "root";
    public $password = "";
    public $db = "mqtt";
    public $conexion;

    function __construct() {
        $this->conexion = mysqli_connect($this->host, $this->user, $this->password, $this->db);
    }
}
?>
