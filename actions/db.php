<?php

function connect_db () {
    // conexion a la db
    $servername = "localhost";
    $username = "toor";
    $password = "contraseña";
    $dbname = "empresa";
    // Crear conexion
    // version para mysql
    $conn = new mysqli($servername, $username, $password, $dbname);
    // version para psql
    // $conn = pg_connect("host=127.0.0.1 port=5432 dbname=$dbname user=$username password=$password");
    // retornar el cursor
    return $conn;
}

function query ($conn, $q) {
    // ejecutar la query
    // para mysql
    return $conn->query($q);
    // para psql
    // return $res=pg_Exec($conn,$q);

}

function disconnect_db ($conn) {
    // cerrar la conexion
    // solo hay en mysql
    $conn->close();
}

?>