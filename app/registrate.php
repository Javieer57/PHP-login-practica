<?php
require 'functions/functions.php';

session_start();

validarSesion();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	/* Limpiamos los datos recibidos */
	$usuario =  htmlspecialchars($_POST['usuario']);
	$usuario =  strtolower($_POST['usuario']);
	$usuario =  trim($_POST['usuario']);
	$usuario =  stripslashes($_POST['usuario']);
	$usuario =  filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING);
	
	$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
	$password2 = filter_var($_POST['password2'], FILTER_SANITIZE_STRING);

	$errores = '';

	/* Se valida si todos los datos fueron capturados */
	if (empty($usuario) or empty($password) or empty($password2)) {
		$errores .= '<li>Por favor rellena todos los campos</li>';
	} else {
		
		conectarBD();

		/* Se valida si el usuario existe en la BD */
		$statement = $conexion->prepare('SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1');
		$statement->execute(array(':usuario' => $usuario));
		$resultado = $statement->fetch();

		# fetch y fetchAll devuelven array con resultados o false		
		if ($resultado != false) {
			$errores .= '<li>El usuario ya está registrado</li>';
		}

		/* Se cambian las contraseñas para que no sean texto plano (NO ES encriptación) */
		$password = hash('sha512', $password);
		$password2 = hash('sha512', $password2);

		/* Se valida que las contraseñas sean las mismas */
		if ($password != $password2) {
			$errores .= '<li>Las contraseñas no son iguales</li>';
		}

		/* Si no hay errores, se inyectan datos en la tabla */
		if ($errores == '') {
			$statement = $conexion->prepare('INSERT INTO usuarios VALUES (null, :user, :pass)');
			$statement->execute(array(':user' => $usuario, ':pass' => $password));
			
			header('Location: login.php');
		}
		
	}
}

require 'views/registrate.view.php';

?>