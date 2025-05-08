<?php
declare(strict_types=1);

error_reporting(E_ALL);
setlocale(LC_TIME, "es_MX.UTF-8");
setlocale(LC_TIME, "spanish");

// Definición de constantes de conexión
define("CON_HOST", "localhost");  // Cambiar si el host es diferente
define("CON_USUARIO", "root");    // Cambiar según tu usuario de MySQL
define("CON_PASSWORD", "");       // Cambiar según tu contraseña de MySQL
define("CON_BDD", "ecoblog");     // Nombre de la base de datos

/**
 * Función para obtener la conexión a la base de datos
 * @return mysqli
 */
function getDBConnection(): mysqli {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Habilita excepciones en caso de error

    try {
        $mysqli = new mysqli(CON_HOST, CON_USUARIO, CON_PASSWORD, CON_BDD);
        $mysqli->set_charset("utf8mb4"); // Establecer el conjunto de caracteres
        return $mysqli;
    } catch (mysqli_sql_exception $e) {
        error_log("Error en la conexión a MySQL: " . $e->getMessage()); // Log de error
        header('Content-Type: application/json');
        echo json_encode([
            "status" => "error",
            "message" => "Error en la conexión a la base de datos. Intente más tarde."
        ]);
        exit;
    }
}

?>
