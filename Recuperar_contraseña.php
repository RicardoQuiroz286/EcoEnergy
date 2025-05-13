<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

session_start();

$mensaje = '';
$mostrarFormularioCodigo = false;
$mostrarFormularioNuevaClave = false;

// Configuración de la base de datos
$host = 'localhost'; // Cambiar a tu host
$db = 'ecoblog'; // Nombre de tu base de datos
$user = 'root'; // Tu usuario de base de datos
$pass = ''; // Tu contraseña de base de datos

// Conexión a la base de datos
$conexion = new mysqli($host, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// FASE 1: Enviar código al correo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'])) {
    $correo_destino = $_POST['correo'];

    if (!filter_var($correo_destino, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'Correo no válido.';
    } else {
        // Verificar si el correo está registrado en la base de datos
        $consulta = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt = $conexion->prepare($consulta);
        $stmt->bind_param('s', $correo_destino);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            // Generar código de recuperación
            $codigo = rand(100000, 999999);
            $_SESSION['codigo_recuperacion'] = $codigo;
            $_SESSION['correo_recuperacion'] = $correo_destino;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'rquiroz@ucol.mx';
                $mail->Password   = 'exsm pyjc majg fwqv';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('rquiroz@ucol.mx', 'EcoEnergy');
                $mail->addAddress($correo_destino);

                $mail->isHTML(true);
                $mail->Subject = 'ECO ENERGY';
                $mail->Body    = "Tu código de recuperación es: <b>$codigo</b><br>Este código es válido por 10 minutos.";

                $mail->send();
                $mensaje = 'Código enviado. Revisa tu correo.';
                $mostrarFormularioCodigo = true;
            } catch (Exception $e) {
                $mensaje = "Error al enviar el correo: {$mail->ErrorInfo}";
            }
        } else {
            $mensaje = 'El correo no está registrado.';
        }
    }
}

// FASE 2: Verificar el código ingresado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
    $codigo_ingresado = $_POST['codigo'];

    if ($_SESSION['codigo_recuperacion'] == $codigo_ingresado) {
        $mensaje = 'Código verificado. Ahora puedes restablecer tu contraseña.';
        $mostrarFormularioNuevaClave = true;
    } else {
        $mensaje = 'Código incorrecto. Intenta nuevamente.';
        $mostrarFormularioCodigo = true;
    }
}

// FASE 3: Guardar nueva contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nueva_contraseña'])) {
    $nueva = $_POST['nueva_contraseña'];
    $confirmacion = $_POST['confirmar_contraseña'];

    if ($nueva === $confirmacion) {
        // Hash de la nueva contraseña
        $nueva_hash = password_hash($nueva, PASSWORD_BCRYPT);

        // Actualizar la contraseña en la base de datos
        $correo_recuperacion = $_SESSION['correo_recuperacion'];
        $consulta_actualizar = "UPDATE usuarios SET contraseña = ? WHERE correo = ?";
        $stmt = $conexion->prepare($consulta_actualizar);
        $stmt->bind_param('ss', $nueva_hash, $correo_recuperacion);

        if ($stmt->execute()) {
            $mensaje = 'Contraseña restablecida con éxito.';
            session_destroy();
            // Redirigir al usuario al inicio de sesión
            header('Location: inicio_sesion.php');
            exit();  // Es importante usar exit() después de header() para detener la ejecución del código
        } else {
            $mensaje = 'Error al actualizar la contraseña. Intenta nuevamente.';
        }
    } else {
        $mensaje = 'Las contraseñas no coinciden.';
        $mostrarFormularioNuevaClave = true;
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="css/iniciosesion.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<div class="container">
    <div class="forms-container">
        <div class="signin-signup">
            <form method="POST" class="sign-in-form">
                <h2 class="title">Recuperar Contraseña</h2>

                <?php if (!empty($mensaje)): ?>
                    <p style="color: <?= strpos($mensaje, 'Error') !== false || strpos($mensaje, 'incorrecto') !== false ? 'red' : 'green' ?>; text-align: center; font-size: 0.9rem; margin-bottom: 10px;">
                        <?= $mensaje ?>
                    </p>
                <?php endif; ?>

                <!-- FASE 1: Ingresar correo -->
                <?php if (!$mostrarFormularioCodigo && !$mostrarFormularioNuevaClave): ?>
                    <div class="input-field">
                        <i class='bx bx-envelope'></i>
                        <input type="email" name="correo" placeholder="Ingresa tu correo" required>
                    </div>
                    <input type="submit" value="Enviar código" class="btn solid">
                <?php endif; ?>

                <!-- FASE 2: Ingresar código -->
                <?php if ($mostrarFormularioCodigo): ?>
                    <div class="input-field">
                        <i class='bx bx-key'></i>
                        <input type="text" name="codigo" placeholder="Ingresa el código recibido" required>
                    </div>
                    <input type="submit" value="Verificar código" class="btn solid">
                <?php endif; ?>

                <!-- FASE 3: Ingresar nueva contraseña -->
                <?php if ($mostrarFormularioNuevaClave): ?>
                    <div class="input-field">
                        <i class='bx bx-lock'></i>
                        <input type="password" name="nueva_contraseña" placeholder="Nueva contraseña" required>
                    </div>
                    <div class="input-field">
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" name="confirmar_contraseña" placeholder="Confirmar contraseña" required>
                    </div>
                    <input type="submit" value="Restablecer contraseña" class="btn solid">
                <?php endif; ?>

                <p class="social-text"><a href="inicio_sesion.php">Volver al inicio de sesión</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
