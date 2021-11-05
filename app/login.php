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
	$password = hash('sha512', $password);

	$errores = '';

	/* Validamo que todos los campos hayan sido capturados */
	if(empty($usuario) or empty($password)){
		$errores .= '<li>Por favor rellena todos los campos</li>';
	} else {

		conectarBD();

		/* Validamos que el usuario exista en la BD */
		// $statement = $conexion->prepare('SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1');
		// $statement->execute(array(':usuario' => $usuario));
		// $resultado = $statement->fetch();

		// if ($resultado == false) {
		// 	$errores .= '<li>El usuario no existe</li>';
		// }

		/* Validamos que el usuario y la contraseña sean correctos */
		$statement = $conexion->prepare('SELECT * FROM usuarios WHERE usuario = :usuario AND contrasena = :pass');
		$statement->execute(array(':usuario' => $usuario, ':pass' => $password));

		$resultado = $statement->fetch();

		/* Si no hubo resultados, arroja error; si hubo, guarda el usuario en la sesión y manda a index que mandará a contenido  */
		if ($resultado == false) {
			$errores .= '<li>Los datos ingresados son incorrectos</li>';
		} else {
			$_SESSION['usuario'] = $usuario;
			header('Location: index.php');
		}
	}

}

require 'views/login.view.php'

?>