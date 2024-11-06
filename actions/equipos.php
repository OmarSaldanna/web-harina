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
	// comprobar en views/views.json
	if ($_SESSION['id_rol'] != 5) {
		// redirigir al login
		redirect("../index.php", $denied_alert);
	}


	//////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Alertas y otras variables /////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////

	// todas las alertas de error que vamos a usar
	$empty_field = "M.toast({html: 'No se aceptan campos vacíos', classes: 'red'});";
	$error = "M.toast({html: 'Algo salió mal, intenta más tarde', classes: 'red'});";

	// alertas de confirmacion
	$deleted = "M.toast({html: 'Equipo eliminado correctamente', classes: 'green rounded'});";
	$created = "M.toast({html: 'Equipo creado correctamente', classes: 'green rounded'});";
	$updated = "M.toast({html: 'Equipo actualizado correctamente', classes: 'green rounded'});";

	// iniciar la conexión
	$conn = connect_db();

	//////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////// Excepción: eliminar equipos /////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////

	// Si llego por get "equipo_delete_id": es que ese usuario se va a eliminar
	// NO CONFUNDIRSE CON "equipo_update_id" que es para updates de usuarios
	// entonces verificamos si si llego la variable y que no este vacia
	if (isset($_GET['equipo_delete_id']) && !empty($_GET['equipo_delete_id'])) {
		// entonces hacemos la query para eliminar el usuario
		$result = query($conn, "DELETE FROM equipo WHERE id_equipo = ".$_GET['equipo_delete_id'].";");
		// verificamos si salio bien
		final_db_check($conn, $result, "../dashboard.php", $deleted."&view=equipos", "../dashboard.php", $error."&view=equipos");
	}

	//////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Recepción de Parámetros ///////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////

	// todos los "name" de los inputs del formulario, recibidas por POST
	$params = array("action", "marca", "modelo", "serie", "garantia_tipo", "estado", "ubicacion", "des_corta", "des_larga", "id_tipo", "id_proveedor", "f_adquisicion", "garantia_vigencia");

	# 1 - verificar campos vacíos
	foreach ($params as $param) {
		// verificar que esten definidos y no vacios
		if (!isset($_POST[$param]) || empty($_POST[$param])) {
			// redirigir al dashboard con alerta de campos vacios
			redirect("../dashboard.php", $empty_field."&view=equipos");
		}
	}

	//////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Procedimientos de DB //////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////

	// remove "action" from params
	$params = array_diff($params, array("action"));

	// returns two string: names and values
	function parse_params($prms) {
		// both strings are for the queries
		$names = "(";
		$values = "(";
		// for each param in prms
		foreach ($prms as $p) {
			// add to names
			$names =  $names.$p.',';
			// add to values considering type
			if (!in_array($p, ["id_tipo", "id_proveedor"])) {
				$values = $values."'".$_POST[$p]."',";
			} else {
				$values = $values.$_POST[$p].',';
			}
		}
		// delete the las , from both arrays
		$names = substr($names, 0, -1);
		$values = substr($values, 0, -1);
		// finally add the last )
		$names = $names.')';
		$values = $values.')';
		// and return
		return array($names, $values);
	}

	// parse the params
	list($names, $values) = parse_params($params);


	// ya que comprobamos que el formulario era correcto, ya podemos ejecutar las acciones, entonces:
	// verificamos que acción se requirió:
	switch ($_POST['action']) {

		# 1 - crear equipos
	    case "create":
	    	// hacer la query de crear usuario
	    	$result = query($conn, "INSERT INTO equipo$names VALUES $values;");
	    	// efectuar el check
	    	final_db_check($conn, $result, "../dashboard.php", $created."&view=equipos", "../dashboard.php", $error."&view=equipos");
	    break;
	    
	    # 2 - actualizar usuarios
	    case "update":
	    	# verificar que si haya llegado el id de actualizacion por GET
	    	if (isset($_GET['equipo_update_id']) || !empty($_GET['equipo_update_id'])) {
	    		// formar la query
	    		$query = "UPDATE equipo SET ";
	    		foreach ($params as $p) {
	    			// lo mismo que antes
	    			if (!in_array($p, ["id_tipo", "id_proveedor"])) {
	    				$query = $query."$p = '".$_POST[$p]."', ";
	    			} else {
	    				$query = $query."$p = ".$_POST[$p].", ";	    				
	    			}
	    		}
	    		// eliminar el ", "
				$query = substr($query, 0, -2);
				// y agregar la condicion final
				$query = $query."WHERE id_equipo = ".$_GET['equipo_update_id'].";";
	    		// efectuar la query
	        	$result = query($conn, $query);
	    		// efectuar el check
	        	final_db_check($conn, $result, "../dashboard.php", $updated."&view=equipos", "../dashboard.php", $error."&view=equipos");
	        
	        // si no llegó el id del usuario para actualizar
	        } else {
	        	redirect("../dashboard.php", $error."&view=equipos");
	        }
	    break;
	    
	    # 3 - error en action
	    default:
	        // redirigir al dashboard con una alerta
			redirect("../dashboard.php", $error."&view=equipos");
	    break;
	}

?>