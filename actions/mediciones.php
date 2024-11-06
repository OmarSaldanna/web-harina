<?php 
	// arrancar el sistema de sesiones
	session_start();

	// importar los modulos de db
	require 'db.php';

	// IMPORTANTE: VERIFICACIÓN DE ID ROL
	$specific_id_role = 5;

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
	if ($_SESSION['id_rol'] != $specific_id_role) {
		// redirigir al login
		redirect("../index.php", $denied_alert);
	}


	//////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Alertas y otras variables /////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////

	// todas las alertas de error que vamos a usar
	$empty_field = "M.toast({html: 'No se aceptan campos vacíos', classes: 'red'});";
	$error = "M.toast({html: 'Algo salió mal, intenta más tarde', classes: 'red'});";
	$bad_type_alert = "M.toast({html: 'Los equipos seleccionados no coinciden', classes: 'red'});";
	$lote_not_found = "M.toast({html: 'No se encontro ese lote', classes: 'red'});";

	// alertas de confirmacion
	$created = "M.toast({html: 'Medicion creada correctamente', classes: 'green rounded'});";

	// iniciar la conexión a la db
	$conn = connect_db();

	//////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Recepción de Parámetros ///////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////

	// todos los "name" de los inputs del formulario, recibidas por POST
	$params = array("id_lote", "equipo", "m1","m2","m3","m4","m5");
	
	# excepcion el switch y el checkbox:
	// el switch recibe null o on, osea Albeo y Farin
	$switch = !isset($_POST['switch']) ? "A" : "F";
	$checkbox = !isset($_POST['checkbox']) ? false : true;

	# 1 - verificar campos vacíos
	foreach ($params as $param) {
		// verificar que esten definidos y no vacios
		if (!isset($_POST[$param]) || empty($_POST[$param])) {
			// redirigir al dashboard con alerta de campos vacios
			redirect("../dashboard.php", $empty_field);
		}
	}

	# 2 - verificar que el id lote exista
	$verificacion_lote = query($conn, "SELECT * FROM lote WHERE id_lote=".$_POST['id_lote'].";");

	if ($verificacion_lote) {
	    // Obtener el número de filas del resultado
	    $num_filas = $verificacion_lote->num_rows;
	    // verificar el numero de filas
	    if ($num_filas != 1) {
	    	// redirigir al dashboard con los datos actuales
			$direction = "Location: ../dashboard.php?alert=$lote_not_found&id_lote=".$_POST['id_lote']."&equipo=".$_POST['equipo']."&switch=".$_POST['switch']."&bad_request=1";
			// agregar todas las medidas a la url
			for ($i = 1; $i <= 5; $i++) {
				$direction = $direction."&m$i=".$_POST["m$i"];
			}
			// redireccionar con todos los datos
			header($direction);
			exit();
	    } 
	} else {
		// redirigir al dashboard con alerta de campos vacios
		redirect("../dashboard.php", $error);
	}

	# 3 - verificar que el equipo tenga el mismo tipo que el puesto en el switch
	list($id_equipo, $tipo) = explode("-", $_POST['equipo']);

	if ($switch != $tipo) {
		// redirigir al dashboard con los datos actuales
		$direction = "Location: ../dashboard.php?alert=$bad_type_alert&id_lote=".$_POST['id_lote']."&equipo=".$_POST['equipo']."&switch=".$_POST['switch']."&bad_request=1";
		// agregar todas las medidas a la url
		for ($i = 1; $i <= 5; $i++) {
			$direction = $direction."&m$i=".$_POST["m$i"];
		}
		// redireccionar con todos los datos
		header($direction);
		exit();
	}

	//////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Selección del Tipo ////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////

	function increment_letter ($l) {
		return chr(ord($l) + 1);
	}

	# el tipo dice si la medición del lote fue por producción o para pedidos de clientes,
	# A es para producción, B,C,D,... son para los de los pedidos

	# Proceso de Selección de Tipo
	# finalmente el tipo, es acumilativo: (ESTO ES POR CADA LOTE)
	# 	si es para ventas:
	# 		si es del mismo equipo que el "tipo mas alto" (alfabéticamnte):
	#			entonces tomar ese tipo y aumentarlo
	# 		si no es del mismo equipo:
	#			entonces tomar ese tipo
	#	si no es para ventas:
	#		entonces el tipo será simplemente A

	# ENTONCES: primero hacemos una query nos va a dar la ultima medición según el tipo
	$ultima_medicion = query($conn, "SELECT * FROM medicion ORDER BY tipo DESC LIMIT 1;");
	# extraemos los datos de la ultima medicion
	$datos_ultima_medicion = $ultima_medicion->fetch_assoc();
	# entonces empezamos el proceso de seleccion de tipo
	if ($checkbox) { // si fue una medicion para ventas
		# si es del mismo equipo
		if ($id_equipo == $datos_ultima_medicion["id_equipo"]) {
			# el tipo será aumentado
			$tipo_medicion = increment_letter($datos_ultima_medicion["tipo"]);
		# si no es del mismo equipo
		} else {
			# el tipo se mantiene
			$tipo_medicion = $datos_ultima_medicion["tipo"];
		}
	# si no es para ventas:
	} else {
		# simplemente el tipo será A
		$tipo_medicion = "A";
	}

	//////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Procedimientos de DB //////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////

	// ya que comprobamos que el formulario era correcto, ya podemos ejecutar las acciones, entonces:
	// verificamos que acción se requirió:

	// metricas de los equipos
	$metricas = array(
	    array("Tenacidad a Extensibilidad (P/L)", "Tenacidad (P)", "Extensibilidad (L)", "Índice de Elasticidad", "Energía de Deformación"),
	    array("Absorción de agua", "Tiempo de desarrollo", "Estabilidad", "Debilitamiento", "Índice de calidad")
	);

	$medidas = '{';
	// seleccionar las metricas segun el equipo
	$metricas_idx = $switch == "A" ? 0 : 1;
	// formar el json de las variables
	for ($i = 1; $i <= 5; $i++) {
		$medidas = $medidas."\"".$metricas[$metricas_idx][$i-1]."\": \"".$_POST["m$i"]."\"";
		if ($i < 5) { $medidas = $medidas.','; }
		else { $medidas = $medidas.'}'; }
	}

	// obtener la fecha actual
	$fecha = date('Y-m-d');
	// mantener las variables asi para facilidad con la string de la query
	$id_usuario = $_SESSION['id_usuario'];
	$id_lote = $_POST['id_lote'];

	$query_content = "INSERT INTO medicion (id_usuario, id_lote, id_equipo, mediciones, fecha, tipo) VALUES ($id_usuario, $id_lote, $id_equipo, '$medidas', '$fecha', '$tipo_medicion');";
	# echo $query_content;

	# 1 - crear medicion
	// hacer la query de crear usuario
	$result = query($conn, $query_content);
	// efectuar el check
	final_db_check($conn, $result, "../dashboard.php", $created, "../dashboard.php", $error);

?>