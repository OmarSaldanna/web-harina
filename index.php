<?php
	// codigo inicial de verificacion de sesion:
	// si ya hay sesion iniciada, redirigimos al dashboard
	// iniciamos el mecanismo de sesiones
	session_start();

	// Verificar si no existe una sesión iniciada
	if(isset($_SESSION['id_usuario'])) {
  	// redirigir al dashboard
		header("Location: dashboard.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="icon" type="image/png" href="assets/icon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="styles/materialize.css">
	<link rel="stylesheet" type="text/css" href="styles/settings.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<title>Login</title>
</head>
<style type="text/css">
	h1 { margin-top: 24vh; }
</style>
<body>
	<!-- titulo -->
	<div class="row">
		<div class="col s10 offset-s1 center">
			<h1>Inicio de Sesión</h1>
		</div>
	</div>
	<!-- y el login -->
	<div class="row">
    <form class="col m4 offset-m4 s12 grey darken-4 white-text z-depth-2" action="actions/index.php" method="POST">
    	<!-- un poco de relleno -->
      <div class="spacer"></div>

      <div class="row">
	      <!--input del email -->
        <div class="input-field col s10 offset-s1">
          <i class="material-icons prefix input-text">person_outline</i>
          <input id="icon_email" type="text" class="input-text validate" name="email" value="<?php echo isset($_GET['email']) || !empty($_GET['email']) ? $_GET['email'] : ""; ?>">
          <label for="icon_email">Correo</label>
        </div>
        <!-- input del password -->
        <div class="input-field col s10 offset-s1">
          <i class="material-icons prefix input-text">fingerprint</i>
          <input id="icon_password" type="password" class="input-text validate" name="password">
          <label for="icon_password">Contraseña</label>
        </div>
      </div>

			<!-- el boton -->
      <div class="row">
      	<div class="col s10 offset-s1">
  				<button class="white black-text btn waves-effect waves-light" type="submit" name="action">Iniciar Sesión
    				<i class="material-icons right">send</i>
  				</button>
      	</div>
      </div>
      <!-- un poco de relleno -->
      <div class="spacer"></div>
    </form>
  </div>
</body>
<script type="text/javascript">
  // Código a ejecutar cuando el DOM se ha cargado
	document.addEventListener("DOMContentLoaded", function() {
		<?php 
			// si es que hubo un error, aquí aparecerá la alerta
			echo $_GET['alert'];
		?>
	});
</script>
<script type="text/javascript" src="scripts/materialize.js"></script>
</html>