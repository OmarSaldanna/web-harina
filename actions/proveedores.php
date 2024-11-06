<?php
// arrancar el sistema de sesiones
session_start();

// importar los modulos de db
require 'db.php';

// IMPORTANTE: VERIFICACIÓN DE ID ROL
$specific_id_role = array(2, 3, 4);

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

# 2 - Comprobar que el usuario tenga el rol
if (!in_array($_SESSION['id_rol'], $specific_id_role))  {
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
$created = "M.toast({html: 'Proveedor añadido correctamente', classes: 'green rounded'});";

// iniciar la conexión a la db
$conn = connect_db();

//////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// Recepción de Parámetros ///////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////

// todos los "name" de los inputs del formulario, recibidas por POST
$params = array("nombre", "fecha", "correo", "sitio_web", "estado");


# 1 - verificar campos vacíos
foreach ($params as $param) {
    // verificar que esten definidos y no vacios
    if (!isset($_POST[$param]) || empty($_POST[$param])) {
        // redirigir al dashboard con alerta de campos vacios
        redirect("../dashboard.php", $empty_field."&view=proveedores");
    }
}



//////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// Procedimientos de DB //////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////

// Verificar si se recibieron los datos del formulario
    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $fecha = $_POST["fecha"];
    $notas = $_POST["correo"];
    $sitio_web = $_POST["sitio_web"];
    $estado = $_POST["estado"];

    $q = "INSERT INTO proveedor (nombre, correo, sitio_web, f_inicio_relacion, estado ) VALUES ('$nombre', '$correo', '$sitio_web', '$fecha', '$estado');";

	$result = query($conn, $q);

    final_db_check($conn, $result, "../dashboard.php", $created."&view=proveedores", "../dashboard.php", $error."&view=proveedores");
    #echo $q;
?>