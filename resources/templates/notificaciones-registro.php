<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    header("Location: login-page.php");
    exit();
}

// Include the database connection file
require_once 'conexion.php';  // Adjust the path if needed

// Validate the connection
if (!isset($conexion)) {
    die("Error: Database connection is not defined.");
}

// The procedure is called and executed
function obtenerOpcionesDropdown($conexion, $tableName)
{
    $stmt = $conexion->prepare("SELECT * FROM $tableName");
    $stmt->execute();
    $result = $stmt->get_result();

    $options = [];
    while ($row = $result->fetch_assoc()) {
        $options[] = $row;
    }
    return $options;
}




// The procedure is called and executed
function obtenerDiccionarioAlertas($conexion)
{
    $stmt = $conexion->prepare("CALL P_ObtenerDiccionarioAlertas()");
    $stmt->execute();
    $result = $stmt->get_result();

    $options = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $options[] = $row;
        }
    }
    return $options;
}

$alertasDiccionario = obtenerDiccionarioAlertas($conexion);
$medios = obtenerOpcionesDropdown($conexion, 'medioNotificacion');
$clientes = obtenerOpcionesDropdown($conexion, 'proyectos');

$successMessage = $errorMessage = '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Notificación</title>

    <!-- Link to CSS-->
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/notificaciones-registro.css">
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/common.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>

    <!-- Development of the common navbar for the project -->
    <nav class="navbar navbar-expand-lg" id="nav_common">
        <div class="container-fluid">
            <a class="navbar-brand" href="common.php" id="nav_logoCommon">
                <img src="/SC502-G1-Proyecto_gestion_de_Alertas/assets/media/logo.png" alt="Logo">
            </a>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="alerta-cliente.php">Alertas por cliente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notificaciones.php">Notificaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="proyectos.php">Proyectos</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Administración
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Administrar usuarios</a></li>
                            <li><a class="dropdown-item" href="#">Administrar clientes</a></li>
                            <li><a class="dropdown-item" href="#">Administrar roles</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div>
                <a class="nav-link" href="cerrar-sesion.php" id="nav_logout">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <section>

        <!-- Container -->
        <div class="container">
            <h2 class="form-title">Registrar nueva notificación:</h2>
        </div>

        <div class="form-grid" id="form_notif">
            <!-- Primera columna -->
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="analista">Analista:</label>
                <input type="text" id="analista" name="analista">
            </div>
            <div class="mb-3">
                <label for="id_Proyecto" class="form-label">Cliente</label>
                <select class="form-select" id="id_Proyecto" name="id_Proyecto" required>
                    <option value="" disabled selected>Seleccione un cliente</option>
                    <?php foreach ($clientes as $cliente) : ?>
                        <option value="<?php echo $cliente['id_Proyecto']; ?>">
                            <?php echo htmlspecialchars($cliente['nombreCliente']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="descripcion">Descripción de notificación:</label>
                <input type="text" id="descripcion" name="descripcion">
            </div>
            <div class="mb-3">
                <label for="id_AlertaDiccionario" class="form-label">Nombre de la Alerta</label>
                <select class="form-select" id="id_AlertaDiccionario" name="id_AlertaDiccionario" required>
                    <option value="" disabled selected>Seleccione una alerta</option>
                    <?php foreach ($alertasDiccionario as $alerta) : ?>
                        <option value="<?php echo $alerta['id_AlertaDiccionario']; ?>">
                            <?php echo htmlspecialchars($alerta['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="dispositivo">Dispositivo:</label>
                <input type="text" id="dispositivo" name="dispositivo">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="fechaIncidencia">Fecha de incidencia:</label>
                <input type="datetime-local" id="fechaIncidencia" name="fechaIncidencia" class="uniform-field">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="fechaNotificacion">Fecha de notificación:</label>
                <input type="datetime-local" id="fechaNotificacion" name="fechaNotificacion" class="uniform-field">
            </div>
            <!-- Segunda columna -->
            <div class="mb-3">
                <label for="id_Medio" class="form-label">Medio de Notificación</label>
                <select class="form-select" id="id_Medio" name="id_Medio" required>
                    <option value="" disabled selected>Seleccione un medio</option>
                    <?php foreach ($medios as $medio) : ?>
                        <option value="<?php echo $medio['id_Medio']; ?>">
                            <?php echo htmlspecialchars($medio['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="grupo">Grupo / Persona notificada:</label>
                <input type="text" id="grupo" name="grupo">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="tiquete">Tiquete Speed-e:</label>
                <input type="text" id="tiquete" name="tiquete">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="comentarios">Comentarios:</label>
                <textarea id="comentarios" name="comentarios" rows="4"></textarea>
            </div>
        </div>
        <!-- Botón de registro -->
        <div class="button-container">
            <button class="button-register">✏️ Registrar notificación</button>
        </div>

    </section>

    <!-- Development of the common footer for the project -->
    <footer class="mt-auto p-2" id="footer_common">
        <div class="container">
            <div class="col">
                <p class="lead text-center" style="font-size: 1rem;">
                    Derechos Reservados Gestor de alertas - Universidad Fidélitas &COPY; 2024
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>