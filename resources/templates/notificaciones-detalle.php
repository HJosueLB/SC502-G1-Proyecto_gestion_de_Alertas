<?php
session_start();
require_once 'conexion.php';

// Check if the user is authenticated
if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    header("Location: login-page.php");
    exit();
}

// Variable to control the visibility of elements
$esAdmin = ($_SESSION['rol'] === 'administrador');

// Get notification ID from the URL
if (!isset($_GET['id'])) {
    header("Location: notificaciones.php");
    exit();
}
$id_notificacion = intval($_GET['id']);

// Fetch notification details
$query = "SELECT n.*, d.nombre AS alerta, p.nombreCliente AS cliente, m.nombre AS medio
          FROM gestoralertas.notificaciones n
          JOIN gestoralertas.diccionarioalertas d ON n.id_Alerta = d.id_AlertaDiccionario
          JOIN gestoralertas.proyectos p ON n.id_cliente = p.id_Proyecto
          JOIN gestoralertas.medioNotificacion m ON n.id_Medio = m.id_Medio
          WHERE n.id_notificacion = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_notificacion);
$stmt->execute();
$resultado = $stmt->get_result();
$notificacion = $resultado->fetch_assoc();

if (!$notificacion) {
    die("No se encontró la notificación.");
}

// Process update form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comentarios = $_POST['comentarios'];
    $analista = $_POST['analista'];
    $descripcion = $_POST['descripcion'];
    $tiquete = $_POST['tiquete'];

    $update = $conexion->prepare("CALL sp_editar_notificacion(?, ?, ?, '', '', ?, ?)");
    $update->bind_param("issss", $id_notificacion, $comentarios, $analista, $descripcion, $tiquete);

    if ($update->execute()) {
        header("Location: notificaciones.php?mensaje=actualizado");
        exit();
    } else {
        $errorMessage = "Error al actualizar la notificación: " . $update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Notificación</title>

    <!-- Link to CSS-->
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/common.css">
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/notificaciones-detalle.css">

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
                    <?php if ($esAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="proyectos.php">Proyectos</a>
                    </li>
                    <?php endif; ?>
                    <?php if ($esAdmin): ?>
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
                    <?php endif; ?>
                </ul>
            </div>
            <div>
                <a class="nav-link" href="cerrar-sesion.php" id="nav_logout">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <section>
        <div class="container mt-5">
            <h2>Detalle de Notificación</h2>

            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="comentarios" class="form-label">Comentarios</label>
                    <input type="text" class="form-control" id="comentarios" name="comentarios" value="<?= htmlspecialchars($notificacion['comentarios']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="analista" class="form-label">Analista</label>
                    <input type="text" class="form-control" id="analista" name="analista" value="<?= htmlspecialchars($notificacion['analista']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= htmlspecialchars($notificacion['descripcion']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="alerta" class="form-label">Alerta</label>
                    <input type="text" class="form-control" id="alerta" value="<?= htmlspecialchars($notificacion['alerta']) ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="cliente" class="form-label">Cliente</label>
                    <input type="text" class="form-control" id="cliente" value="<?= htmlspecialchars($notificacion['cliente']) ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="medio" class="form-label">Medio</label>
                    <input type="text" class="form-control" id="medio" value="<?= htmlspecialchars($notificacion['medio']) ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="tiquete" class="form-label">Tiquete Speed-e</label>
                    <input type="text" class="form-control" id="tiquete" name="tiquete" value="<?= htmlspecialchars($notificacion['tiqueteSpeede']) ?>">
                </div>

                <div class="mb-3">
                    <label for="fechaNotificacion" class="form-label">Fecha de Notificación</label>
                    <input type="text" class="form-control" id="fechaNotificacion" value="<?= htmlspecialchars($notificacion['fechaNotificacion']) ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="fechaIncidencia" class="form-label">Fecha de Incidencia</label>
                    <input type="text" class="form-control" id="fechaIncidencia" value="<?= htmlspecialchars($notificacion['fechaIncidencianotificaciones']) ?>" disabled>
                </div>
                <?php if ($esAdmin): ?>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <?php endif; ?>
                <a href="notificaciones.php" class="btn btn-secondary">Regresar</a>
            </form>
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