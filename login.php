<?php
session_start();
$host = "localhost"; // Servidor
$user = "root"; // Usuario de MySQL
$pass = ""; // Contraseña de MySQL (déjala vacía si no has configurado una)
$dbname = "Ecoblog"; // Nombre de tu base de datos

// Conectar a la base de datos
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se enviaron los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    // Consulta SQL para verificar usuario
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        // Verificar la contraseña (si está encriptada con password_hash)
        if (password_verify($contrasena, $usuario["contrasena"])) {
            $_SESSION["usuario"] = $usuario["correo"];
            header("Location: dashboard.php"); // Redirigir a la página principal
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location.href='index.html';</script>";
        }
    } else {
        echo "<script>alert('Correo no registrado'); window.location.href='index.html';</script>";
    }
    $stmt->close();
}

$conn->close();
?>
