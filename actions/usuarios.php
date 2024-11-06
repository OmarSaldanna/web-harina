<?php 
	// arrancar el sistema de sesiones
	session_start();

	// importar los modulos de db
	require 'db.php';

	// definir una alertas de error
	$error_alert = "M.toast({html: 'Ups! Ocurrió un error', classes: 'red'});";
	$denied_alert = "M.toast({html: 'Permiso Denegado', classes: 'red'});";

	// funciones implementadas por repetición en el código
	
	// funcion para redirigir con alerta
	function redirect ($to, $msg) {
		header("Location: $to?alert=$msg");
		exit();
	}

	// funcion para las comprobaciones finales de db
	function final_db_check ($conn, $result, $to, $msg, $another, $err) {
		// cerrar el cursor de db
		disconnect_db($conn);
		// verificar que la query haya sido existosa
		if ($result) {
			// redirigir en caso de exito
			redirect($to, $msg);
		} else {
			// redirigir en caso de error
			redirect($another, $err);
		}
	}

	//////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Comprobaciones de Sesion //////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////
	
	# 1 - Comprobar que haya sesion activa
	if (!isset($_SESSION['id_usuario'])) {
  		// redirigir al login
  		redirect("../index.php", $error_alert);
	}
	
	# 2 - Comprobar que el usuario sea admin
	if ($_SESSION['id_rol'] != 1) {
		// redirigir al login
		redirect("../index.php", $denied_alert);
	}


	//////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Alertas y otras variables /////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////

	// todas las alertas de error que vamos a usar
	$empty_field = "M.toast({html: 'No se aceptan campos vacíos', classes: 'red'});";
	$incorrect_email = "M.toast({html: 'Correo incorrecto', classes: 'red'});";
	$incorrect_password = "M.toast({html: 'Las contraseñas no coinciden', classes: 'red'});";
	$error = "M.toast({html: 'Algo salió mal, intenta más tarde', classes: 'red'});";

	// alertas de confirmacion
	$deleted = "M.toast({html: 'Usuario eliminado correctamente', classes: 'green rounded'});";
	$created = "M.toast({html: 'Usuario creado correctamente', classes: 'green rounded'});";
	$updated = "M.toast({html: 'Usuario actualizado correctamente', classes: 'green rounded'});";

	// iniciar la conexión
	$conn = connect_db();

	//////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////// Excepción: eliminar usuarios ////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////

	// Si llego por get "user_id": es que ese usuario se va a eliminar
	// NO CONFUNDIRSE CON "user_update_id" que es para updates de usuarios
	// entonces verificamos si si llego la variable y que no este vacia
	if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
		// entonces hacemos la query para eliminar el usuario
		$result = query($conn, "DELETE FROM usuario WHERE usuario.id_usuario = ".$_GET['user_id'].";");
		// verificamos si salio bien
		final_db_check($conn, $result, "../dashboard.php", $deleted, "../dashboard.php", $error);
	}

	//////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Recepción de Parámetros ///////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////

	// todos los "name" de los inputs del formulario, recibidas por POST
	$params = array("action", "name", "email", "password", "repeat", "role");

	# 1 - verificar campos vacíos
	foreach ($params as $param) {
		// verificar que esten definidos y no vacios
		if (!isset($_POST[$param]) || empty($_POST[$param])) {
			// redirigir al dashboard con alerta de campos vacios
			redirect("../dashboard.php", $empty_field);
		}
	}

	# 2 - verificar el email
	if (strpos($_POST['email'], "@") == false) {
		// redirigir al dashboard con una alerta
		redirect("../dashboard.php", $incorrect_email);
	}

	# 3 - verificar que las contraseñas coincidan
	if ($_POST['password'] != $_POST['repeat']) {
		// primero verificar si se está actualizando, esto se implemento porque
		// se borraban los values de los inputs al dar error. Entonces para que se conserven:
		if (isset($_GET['user_update_id']) || !empty($_GET['user_update_id'])) {
			// redirigir al dashboard con una alerta
			header("Location: ../dashboard.php?alert=$incorrect_password&user_id=".$_GET['user_update_id']);
			exit();
		} else {
			// redirigir al dashboard con una alerta
			redirect("../dashboard.php", $incorrect_password);	
		}
		
	}

	//////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Procedimientos de DB //////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////

	// ya que comprobamos que el formulario era correcto, ya podemos ejecutar las acciones, entonces:
	// verificamos que acción se requirió:
	switch ($_POST['action']) {

		# 1 - crear usuarios 
	    case "create":
	    	// hacer la query de crear usuario
	    	$result = query($conn, "INSERT INTO usuario(nombre, contra, id_rol, correo) VALUES (\"".$_POST['name']."\", \"".$_POST['password']."\", ".$_POST['role'].", \"".$_POST['email']."\");");
	    	// efectuar el check
	    	final_db_check($conn, $result, "../dashboard.php", $created, "../dashboard.php", $error);
	    break;
	    
	    # 2 - actualizar usuarios
	    case "update":
	    	# verificar que si haya llegado el id de actualizacion por GET
	    	if (isset($_GET['user_update_id']) || !empty($_GET['user_update_id'])) {
	    		// efectuar la query
	        	$result = query($conn, "UPDATE usuario SET nombre=\"".$_POST['name']."\", contra=\"".$_POST['password']."\", id_rol=".$_POST['role'].", correo=\"".$_POST['email']."\" WHERE id_usuario = ".$_GET['user_update_id'].";");
	    		// efectuar el check
	        	final_db_check($conn, $result, "../dashboard.php", $updated, "../dashboard.php", $error);
	        
	        // si no llegó el id del usuario para actualizar
	        } else {
	        	redirect("../dashboard.php", $error);
	        }
	    break;
	    
	    # 3 - error en action
	    default:
	        // redirigir al dashboard con una alerta
			redirect("../dashboard.php", $error);
	    break;
	}

?>