<?php
	// codigo inicial de verificacion de sesion:
	// si no hay sesion iniciada, redirigimos al dashboard
	// iniciamos el mecanismo de sesiones
	session_start();
	// Verificar si existe una sesión iniciada
	if(!isset($_SESSION['id_usuario'])) {
  	// redirigir al dashboard
		header("Location: index.php");
		exit();
	}

	////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////// lectura de datos del rol //////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////

	// si si hay sesion, vamos a buscar la info del rol
	// importamos las funciones de la db
	require "actions/db.php";
	// conectamos con la db
	$conn = connect_db();
	// ejecutamos la query
	$result_role = query($conn, "SELECT * FROM rol WHERE id_rol=".$_SESSION['id_rol'].";");
	// leemos los resultados
	$role = $result_role->fetch_assoc();
	
	////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////// lectura vistas del .json //////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////

	// este archivo recibira el la fila del rol de sql y ajustará el navbar
  // segun el rol del usuario.

  // hay que leer el json donde estan las vistas
  $jsonData = file_get_contents('views/views.json');
  // decodificar el json
  $views = json_decode($jsonData, true);
  // seleccion
  $role_views = $views[strval($_SESSION['id_rol'])];

	////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////// ajuste de vista actual ////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////

	// codigo para las vistas: hay dos casos:
	$current_view = "";
	# A - viene del login: $_GET['view'] no está definido
	if (!isset($_GET['view']) || empty($_GET['view'])) {
		// entonces renderizar la primera vista del rol
		$current_view = $role_views[0];
	}
	# B - redirigido del nav: $_GET['view'] está definido
	else {
		// entonces renderizar la vista seleccionada
		$current_view = $_GET['view'];
	}

?>
<!DOCTYPE html>
<html>
<head>
	<!-- codificacion de caracteres -->
	<meta charset="utf-8">
	<!-- icono de la ventana -->
	<link rel="icon" type="image/png" href="assets/icon.png">
	<!-- un ajuste de ventana, mero protocolo -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- importar los css, el nuestro y el de materialize -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<!-- importar los iconos de google de materialize -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
	<title><?php echo $role['rol']; ?></title>
</head>
<style type="text/css">
	/* para centrar el contenido del nav */
	nav { padding: 0 1rem; box-shadow: none !important; }
	/* para los botones de vistas */
	.nav-button { text-transform: capitalize; font-weight: bold; }
	.title { text-transform: capitalize; }
	/* imagen del sidebar	*/
	.img-sidebar { width: 80%; border-radius: 100%; margin-left: 10%; margin-top: 3rem; }
	/* dimensiones del sidebar */
	.sidebar { height: 94vh; position: fixed; }
	/* ajustes de la box de las vistas */
	.view-row {  }
	.space { padding-top: 2rem; }
	.space-b { padding-bottom: 2rem; }
</style>
<body>
	<!-- el navbar o header -->
	<!-- además aqui adentro ya se lee el json, las vistas estan en $role_views como array -->
	<?php require 'components/navbar.php'; ?>
	
	<div class="row">
		<!-- la barra lateral, pero dentro de una row -->
		<?php require 'components/sidebar.php'; ?>
		<!-- y la vista: dentro del espacio restante como un row -->
		<div class="col s8 offset-s3"> 
			<!-- aqui metemos la vista -->
			<div class="row view-row">
				<?php
					// echo 'views/'.$current_view.'.php';
					require 'views/'.$current_view.'.php';
				?>
			</div>
		</div>
	</div>

</body>
<script type="text/javascript">
  // Código a ejecutar cuando el DOM se ha cargado
	document.addEventListener("DOMContentLoaded", function() {
		// una alerta de bienvenida
		<?php
			// si no se ha mandado la alerta inicial
			if (!$_SESSION["initial_alert"]) {
				// la mandamos
				echo "M.toast({html: 'Bienvenido de vuelta ".explode(" ", $_SESSION['nombre'])[0]." 😄', classes: 'blue rounded'});";
				// y decimos que ya se mando
				$_SESSION["initial_alert"] = True;
			}

			// si recibimos alguna alerta
			if (isset($_GET['alert'])) {
				// mandarla
				echo $_GET['alert'];
			}
		?>
	});
</script>
<script type="text/javascript" src="scripts/materialize.js"></script>
</html>
<?php 
	// y cerramos la conexion finalmente.
	disconnect_db($conn);
?>