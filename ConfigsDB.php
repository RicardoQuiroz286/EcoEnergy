<?php
declare(strict_types=1);

error_reporting(E_ALL);
setlocale(LC_TIME, "es_MX.UTF-8");
setlocale(LC_TIME, "spanish");

define("CON_HOST", "localhost");  // Cambiar si el host es diferente
define("CON_USUARIO", "root");    // Cambiar según tu usuario de MySQL
define("CON_PASSWORD", "");       // Cambiar según tu contraseña de MySQL
define("CON_BDD", "ecoblog");     // Nombre de la base de datos

$mysqli = new mysqli(CON_HOST, CON_USUARIO, CON_PASSWORD, CON_BDD);

if ($mysqli->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    die();
}

$mysqli->set_charset("utf8mb4");  // Establecer la codificación de caracteres
?>
