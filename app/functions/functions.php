<?php

function validarSesion(){
	if (isset($_SESSION['usuario'])) {
		header('Location: index.php');
	}
}

function conectarBD(){
	try {
		global $conexion;
		$conexion = new PDO('mysql:host=localhost;dbname=practica_login', 'root', '');
	} catch (PDOException $e) {
		echo 'Error: ' . $e->getMessage();
	}
}

?>