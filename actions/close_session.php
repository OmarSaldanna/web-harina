<?php 
	// Iniciar el mecanismo de sesiones
	session_start();

    // la alerta de despedida
	$bye_alert = "M.toast({html: 'Nos vemos luego ".explode(" ", $_SESSION['nombre'])[0]." 👋', classes: 'blue rounded'});";

	// Cerrar la sesión
    session_unset(); // Eliminar todas las variables de sesión
    session_destroy(); // Destruir la sesión
    
    // y redirigir al login con la alerta de adios
    header("Location: ../index.php?alert=$bye_alert");
	exit();
?>