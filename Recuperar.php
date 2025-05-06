<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("ConfigsDB.php");

    $correo = filter_input(INPUT_POST, "correo", FILTER_SANITIZE_EMAIL);

    if (!$correo) {
        echo "Correo inválido.";
        exit;
    }

    
    <!-- Recuperar.php -->
<form action="EnviarCorreo.php" method="POST">
    <input type="email" name="correo" placeholder="Ingresa tu correo" required>
    <button type="submit">Recuperar contraseña</button>
</form>


    $query = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario encontrado
        $nueva_contraseña = bin2hex(random_bytes(4)); // 8 caracteres seguros
        $hash_nueva = password_hash($nueva_contraseña, PASSWORD_DEFAULT);

        $update = "UPDATE usuarios SET contraseña = ? WHERE correo = ?";
        $update_stmt = $mysqli->prepare($update);
        $update_stmt->bind_param("ss", $hash_nueva, $correo);
        $update_stmt->execute();

        echo "<h3>Tu nueva contraseña temporal es: <strong>$nueva_contraseña</strong></h3>";
        echo "<p>Por favor inicia sesión y cambia tu contraseña.</p>";
    } else {
        echo "Este correo no está registrado.";
    }

    $stmt->close();
    $mysqli->close();
}
?>
