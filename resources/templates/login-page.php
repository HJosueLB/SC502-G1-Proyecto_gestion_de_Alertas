<?php
session_start();
include 'conexion.php';

// Variable para manejar mensajes de error
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpiar y validar entrada
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $contraseña = $_POST['contraseña'];

    // Validar que los campos no estén vacíos
    if (empty($correo) || empty($contraseña)) {
        $error = "Por favor, complete todos los campos.";
    } else {
        // Preparar consulta para buscar en empleados
        $stmt = $conexion->prepare("
            SELECT idEmpleado AS id, contraseña, rol 
            FROM empleado 
            WHERE correo = ?
        ");
        $stmt->bind_param('s', $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Si no se encuentra en empleados, buscar en clientes
        if ($resultado->num_rows === 0) {
            $stmt = $conexion->prepare("
                SELECT idCliente AS id, contraseña, rol 
                FROM cliente 
                WHERE correo = ?
            ");
            $stmt->bind_param('s', $correo);
            $stmt->execute();
            $resultado = $stmt->get_result();
        }

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            // Verificar contraseña (ajustar según tu método de almacenamiento)
            if ($contraseña === $usuario['contraseña']) {
                // Regenerar ID de sesión para prevenir secuestro de sesión
                session_regenerate_id(true);
                
                // Guardar datos de sesión
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['rol'] = $usuario['rol'];
                $_SESSION['correo'] = $correo;
                
                // Redirigir según el rol
                if ($usuario['rol'] == 'administrador') {
                    header('Location: common.php');
                } elseif ($usuario['rol'] == 'cliente'){
                    header('Location: common.php');
                }
                exit();
            } else {
                $error = "Correo o contraseña incorrectos.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login</title>
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/login-page.css">
</head>

<body>
    <div class="video-background">
        <video autoplay muted loop>
            <source src="/SC502-G1-Proyecto_gestion_de_Alertas/assets/media/fondo_login.mp4" type="video/mp4">
            Tu navegador no soporta el video en fondo.
        </video>
    </div>

    <div class="login-container">
        <!-- Logo -->
        <img src="/SC502-G1-Proyecto_gestion_de_Alertas/assets/media/logo.png" alt="Logo OB Group">

        <!-- Título del login-->
        <h2>Gestión de Alertas<br>NOC & SOC</h2>

        <!-- Mostrar mensaje de error -->
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de login -->
        <form method="POST" action="">
            <!-- Campo de correo -->
            <input type="text" name="correo" placeholder="Correo electrónico" required 
                   value="<?php echo isset($correo) ? htmlspecialchars($correo) : ''; ?>">

            <!-- Campo de contraseña -->
            <input type="password" name="contraseña" placeholder="Contraseña" required>

            <!-- Opción de guardar datos de ingreso -->
            <div class="remember">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">¿Desea guardar los datos de ingreso?</label>
            </div>

            <!-- Botón de ingreso -->
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>