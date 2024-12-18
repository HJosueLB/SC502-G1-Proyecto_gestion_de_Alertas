<?php
// Verificación de la sesión
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    header("Location: login-page.php");
    exit();
}

// Conexión a la base de datos
require_once 'conexion.php';

// Variable to control the visibility of elements
$esAdmin = ($_SESSION['rol'] === 'administrador');

// Obtener el ID del proyecto desde la URL
if (isset($_GET['id'])) {
    $id_proyecto = $_GET['id'];
    $query = "SELECT * FROM proyectos WHERE id_Proyecto = $id_proyecto";
    $resultado = $conexion->query($query);
    $proyecto = $resultado->fetch_assoc();
}

// Si se ha enviado el formulario de editar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $nombre_proyecto = $_POST['nombre_proyecto'];
    $nombreCliente = $_POST['nombreCliente'];
    $vendedor_id = $_POST['vendedor_id'];
    $gestor_contrato = $_POST['gestor_contrato'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $tipo_contrato = $_POST['tipo_contrato'];
    $idUnidadNegocio = $_POST['idUnidadNegocio'];
    $cobros_mensuales = $_POST['cobros_mensuales'];
    $id_servicio = $_POST['id_servicio'];

    // Llamada al procedimiento almacenado para editar el proyecto
    $query = "CALL editar_proyecto(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ississdssss", $id_proyecto, $nombre_proyecto, $nombreCliente, $vendedor_id, $gestor_contrato, $fecha_inicio, $fecha_fin, $tipo_contrato, $idUnidadNegocio, $cobros_mensuales, $id_servicio);
    $stmt->execute();
    header("Location: proyectos.php"); // Redirigir a la lista de proyectos
    exit();
}

// Si se ha enviado el formulario de eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    // Llamada al procedimiento almacenado para eliminar el proyecto
    $query = "CALL eliminar_proyecto(?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_proyecto);
    $stmt->execute();
    header("Location: proyectos.php"); // Redirigir a la lista de proyectos
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Proyecto</title>
  <!-- Link to CSS-->
  <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/common.css">
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/proyectos-detalle.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        <div class="container">
            <h2>Proyecto: <?= htmlspecialchars($proyecto['nombre_proyecto']) ?></h2>

            <!-- Formulario para editar el proyecto -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nombre_proyecto">Nombre del Proyecto:</label>
                    <input type="text" id="nombre_proyecto" name="nombre_proyecto" value="<?= htmlspecialchars($proyecto['nombre_proyecto']) ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="nombreCliente">Nombre del Cliente:</label>
                    <input type="text" id="nombreCliente" name="nombreCliente" value="<?= htmlspecialchars($proyecto['nombreCliente']) ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="vendedor_id">Vendedor:</label>
                    <input type="text" id="vendedor_id" name="vendedor_id" value="<?= htmlspecialchars($proyecto['vendedor_id']) ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="gestor_contrato">Gestor de Contrato:</label>
                    <input type="text" id="gestor_contrato" name="gestor_contrato" value="<?= htmlspecialchars($proyecto['gestor_contrato']) ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="fecha_inicio">Fecha Inicio:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= $proyecto['fecha_inicio'] ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="fecha_fin">Fecha Fin:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" value="<?= $proyecto['fecha_fin'] ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="tipo_contrato">Tipo de Contrato:</label>
                    <input type="text" id="tipo_contrato" name="tipo_contrato" value="<?= htmlspecialchars($proyecto['tipo_contrato']) ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="idUnidadNegocio">Unidad de Negocio:</label>
                    <input type="text" id="idUnidadNegocio" name="idUnidadNegocio" value="<?= htmlspecialchars($proyecto['idUnidadNegocio']) ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="cobros_mensuales">Cobros Mensuales:</label>
                    <input type="text" id="cobros_mensuales" name="cobros_mensuales" value="<?= $proyecto['cobros_mensuales'] ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="id_servicio">Servicio Contratado:</label>
                    <input type="text" id="id_servicio" name="id_servicio" value="<?= htmlspecialchars($proyecto['id_servicio']) ?>" class="form-control" required>
                </div>

                <button type="submit" name="editar" class="btn btn-primary">Guardar Cambios</button>
                <button type="submit" name="eliminar" class="btn btn-danger">Eliminar Proyecto</button>
            </form>
        </div>
    </section>

    <footer class="mt-auto p-2">
        <div class="container">
            <p class="text-center">Derechos Reservados Gestor de alertas - Universidad Fidélitas &COPY; 2024</p>
        </div>
    </footer>
</body>

</html>
