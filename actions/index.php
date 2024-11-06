<?php 

	// todas las alertas: en casos de error
	$incorrect_password = "M.toast({html: 'Contraseña incorrecta', classes: 'red'});";
	$incorrect_email = "M.toast({html: 'Correo incorrecto', classes: 'red'});";
	$user_not_found = "M.toast({html: 'Correo no encontrado', classes: 'red'});";
	$empty_field = "M.toast({html: 'No se aceptan campos vacíos', classes: 'red'});";

	// cachar las variables
	$email = $_POST['email'];
	$password = $_POST['password'];

	//////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Verificaciones y Procesos /////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////

	// 1 - verificar que no haya campos vacios
	// para email
	if (!isset($email) || empty($email)) {
		// redirigir al login con una alerta
		header("Location: ../index.php?alert=$empty_field&email=$email");
		exit();
	}
	// para password
	if (!isset($password) || empty($password)) {
		// redirigir al login con una alerta
		header("Location: ../index.php?alert=$empty_field&email=$email");
		exit();
	}

	// 2 - verificar que el mail este bien escrito, que tenga @
	if (strpos($email, "@") == false) {
		// redirigir al login con una alerta
		header("Location: ../index.php?alert=$incorrect_email&email=$email");
		exit();
	}

	// para este punto, ya requerimos usar la db, entonces:
	// importar las funciones de db
	require 'db.php';
	// hacer la conexion
	$conn = connect_db();
	// hacer una query: buscar el correo
	$result = query($conn, "SELECT * FROM usuario WHERE correo='$email';");
	// cerrar la conexion
	disconnect_db($conn);

	// 3 - verificar que el usuario exista
	// esto cuenta las filas del resultado
	$rowCount = $result->num_rows;


	if ($rowCount < 1) {
		// redirigir al login con una alerta
		header("Location: ../index.php?alert=$incorrect_email&email=$email");
		exit();
	}

	// 4 - verificar la contraseña
	// seleccionar la contraseña de la db de la primera coincidencia
	$row = $result->fetch_assoc();
	$password_correct = $row['contra'];
	// comparar las contraseñas
	if ($password_correct != $password) {
		// redirigir al login con una alerta
		header("Location: ../index.php?alert=$incorrect_password&email=$email");
		exit();
	}

	//////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Inicio se sesion //////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////

	// esto se hace suponiendo que no hay sesion iniciada
	// inicia la sesion
	session_start();
	// almacena datos del usuario en la sesion
	$_SESSION['id_usuario'] = $row["id_usuario"];
	$_SESSION['correo'] = $row["correo"];
	$_SESSION['nombre'] = $row["nombre"];
	$_SESSION['id_rol'] = $row["id_rol"];
	// este campo es para la alerta inicial del dashboard
	$_SESSION["initial_alert"] = False;
	// redirigir al dashboard
	header("Location: ../dashboard.php");
	exit();

?>