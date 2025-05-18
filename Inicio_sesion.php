<?php
session_start();

// Habilitar la visualización de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir archivo de configuración de la base de datos
require_once "ConfigsDB.php";

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contraseña'];

    // Conectar con la base de datos
    $mysqli = getDBConnection();
    $query = "SELECT * FROM usuarios WHERE correo = ? AND contraseña = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss', $correo, $contrasena);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar credenciales
    if ($resultado->num_rows > 0) {
        $_SESSION['usuario'] = $correo;
        header("Location: indexsi.php");
        exit();
    } else {
        $_SESSION['error_login'] = "Correo o contraseña incorrectos.";
        header("Location: iniciosesion.php");
        exit();
    }
}

// Definir el mensaje de error si existe
$error_login = isset($_SESSION['error_login']) ? $_SESSION['error_login'] : '';
unset($_SESSION['error_login']); // Borrar el error después de mostrarlo
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="css/iniciosesion.css">
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <!-- Formulario de Inicio de Sesión -->
                <form action="iniciosesion.php" method="POST" class="sign-in-form">
                    <h2 class="title dynamic-deepl">Inicia Sesión</h2>

                    <!-- Mostrar mensaje de error -->
                    <?php if (!empty($error_login)): ?>
                        <p style="color: red; text-align: center;"><?php echo $error_login; ?></p>
                    <?php endif; ?>

                    <div class="input-field">
                        <i class='bx bx-user-circle'></i>
                        <input type="email" name="correo" placeholder="Correo electrónico" class="dynamic-deepl" required />
                    </div>

                    <div class="input-field">
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" name="contraseña" id="login-password" class="dynamic-deepl" placeholder="Contraseña" required autocomplete="on" />
                        <i class="bx bx-show toggle-password" id="toggle-login-password"></i> 
                    </div>

                    <div class="remember-forgot">
                        <label class="dynamic-deepl">
                            <input type="checkbox" id="remember-me"> Recordar contraseña
                        </label>
                        <a href="recuperar_contraseña.php" id="forgot-password" class="dynamic-deepl">¿Olvidaste tu contraseña?</a>
                    </div>

                    <input type="submit" value="Inicia Sesión" class="btn solid dynamic-deepl" />
                </form>

                <!-- Formulario de Registro con Confirmar Contraseña -->
                <form action="registro.php" method="POST" class="sign-up-form" onsubmit="return validateRegister(event)">
                    <h2 class="title dynamic-deepl">Regístrate</h2>
                                        
                    <div class="input-field">
                        <i class='bx bx-envelope'></i>
                        <input type="email" name="correo" id="register-email" placeholder="Correo electrónico" class="dynamic-deepl" required />
                    </div>
                                        
                    <div class="input-field">
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" name="contraseña" id="register-password" placeholder="Contraseña" class="dynamic-deepl" required autocomplete="off" />
                        <i class="bx bx-show toggle-password" id="toggle-register-password"></i>
                    </div>
                                        
                    <div class="input-field">
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" name="confirmar_contraseña" id="confirm-password" placeholder="Confirmar Contraseña" class="dynamic-deepl" required autocomplete="off" />
                        <i class="bx bx-show toggle-password" id="toggle-confirm-password"></i>
                    </div>
                                        
                    <input type="submit" class="btn dynamic-deepl" value="Registrarse" />
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3 class="dynamic-deepl">¿Nuevo Aquí?</h3>
                    <p class="dynamic-deepl">¡Regístrate en segundos y empieza a explorar!</p>
                    <button class="btn transparent dynamic-deepl" id="sign-up-btn">Regístrate</button>
                </div>
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3 class="dynamic-deepl">¿Ya tienes cuenta?</h3>
                    <p class="dynamic-deepl">Inicia sesión y continúa explorando</p>
                    <button class="btn transparent dynamic-deepl" id="sign-in-btn">Inicia Sesión</button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/app.js"></script>
    <script src="diccionariolocal.js"></script>
</body>
</html>