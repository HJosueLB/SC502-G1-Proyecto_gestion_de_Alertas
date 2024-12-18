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
function obtenerOpcionesDropdown($conexion, $tableName) {
    $stmt = $conexion->prepare("SELECT * FROM $tableName");
    $stmt->execute();
    $result = $stmt->get_result();

    $options = [];
    while ($row = $result->fetch_assoc()) {
        $options[] = $row;
    }
    return $options;
}

// Fetch dropdown options
$proyectos = obtenerOpcionesDropdown($conexion, 'proyectos');
$vendedor = obtenerOpcionesDropdown($conexion, 'vendedor');
$unidadnegocio = obtenerOpcionesDropdown($conexion, 'unidadnegocio');
$servicio = obtenerOpcionesDropdown($conexion, 'servicio');

// Success and error message initialization
$successMessage = $errorMessage = '';

// The procedure is called and executed when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreCliente = $conexion->real_escape_string($_POST['nombreCliente']);
    $nombre_proyecto = $conexion->real_escape_string($_POST['nombre_proyecto']);
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $tipo_contrato = $conexion->real_escape_string($_POST['tipo_contrato']);
    $vendedor_id = intval($_POST['vendedor_id']);
    $gestor_contrato = $conexion->real_escape_string($_POST['gestor_contrato']);
    $idUnidadNegocio = intval($_POST['idUnidadNegocio']);
    $cobros_mensuales = $conexion->real_escape_string($_POST['cobros_mensuales']);
    $id_servicio = intval($_POST['id_servicio']);

    // Llamada al procedimiento almacenado para registrar el proyecto
$stmt = $conexion->prepare("CALL sp_agregar_proyecto(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssss", $nombre_proyecto, $fecha_inicio, $fecha_fin, $tipo_contrato, $gestor_contrato, $cobros_mensuales, $vendedor_id, $idUnidadNegocio, $servicio_id, $nombreCliente);

if ($stmt->execute()) {
    $successMessage = "Proyecto registrado correctamente.";
} else {
    $errorMessage = "Error al registrar el proyecto. Por favor, inténtelo de nuevo.";
}

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
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/proyectos-registro_p1.css">

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
        <div class="container mt-5">
            <h2>Registrar Nuevo Proyecto</h2>
            <form method="POST" action="">

                <div class="mb-3">
                    <label for="nombreCliente" class="form-label">Cliente:</label>
                    <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" required>
                </div>

                <div class="mb-3">
                    <label for="nombre_proyecto" class="form-label">Nombre del proyecto</label>
                    <input type="text" class="form-control" id="nombre_proyecto" name="nombre_proyecto" required>
                </div>

                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                </div>

                <div class="mb-3">
                    <label for="fecha_fin" class="form-label">Fecha de fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                </div>

                <div class="mb-3">
                    <label for="tipo_contrato" class="form-label">Tipo de contrato</label>
                    <input type="text" class="form-control" id="tipo_contrato" name="tipo_contrato" required>
                </div>

                <div class="mb-3">
                    <label for="vendedor_id" class="form-label">Vendedor</label>
                    <select class="form-select" id="vendedor_id" name="vendedor_id" required>
                        <option value="" disabled selected>Seleccione un vendedor</option>
                        <?php foreach ($vendedor as $vend) : ?>
                            <option value="<?= $vend['vendedor_id'] ?>"><?= htmlspecialchars($vend['nombre_vendedor']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="gestor_contrato" class="form-label">Gestor de contrato</label>
                    <input type="text" class="form-control" id="gestor_contrato" name="gestor_contrato">
                </div>

                <div class="mb-3">
                    <label for="idUnidadNegocio" class="form-label">Unidad de negocio</label>
                    <select class="form-select" id="idUnidadNegocio" name="idUnidadNegocio" required>
                        <option value="" disabled selected>Seleccione una unidad de negocio</option>
                        <?php foreach ($unidadnegocio as $unidad) : ?>
                            <option value="<?= $unidad['idUnidadNegocio'] ?>"><?= htmlspecialchars($unidad['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="cobros_mensuales" class="form-label">Cobros mensuales</label>
                    <input type="text" class="form-control" id="cobros_mensuales" name="cobros_mensuales">
                </div>

                <div class="mb-3">
                    <label for="id_servicio" class="form-label">Servicio contratado</label>
                    <select class="form-select" id="id_servicio" name="id_servicio" required>
                        <option value="" disabled selected>Seleccione un servicio</option>
                        <?php foreach ($servicio as $serv) : ?>
                            <option value="<?= $serv['id_servicio'] ?>"><?= htmlspecialchars($serv['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="button-container">
                    <button type="submit" class="btn btn-primary" id="RegisterButton">Registrar Proyecto</button>
                    <a href="proyectos.php" class="btn btn-secondary" id="CancelButton">Cancelar</a>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto p-2" id="footer_common">
        <div class="container">
            <div class="col">
                <p class="lead text-center" style="font-size: 1rem;">
                    Derechos Reservados - Universidad Fidélitas &COPY; 2024
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($successMessage): ?>
<script>
    Swal.fire({
        title: '¡Éxito!',
        text: '<?php echo $successMessage; ?>',
        icon: 'success',
        confirmButtonText: 'Aceptar'
    }).then(() => {
        window.location.href = 'proyectos.php';
    });
</script>
<?php elseif ($errorMessage): ?>
<script>
    Swal.fire({
        title: '¡Error!',
        text: '<?php echo $errorMessage; ?>',
        icon: 'error',
        confirmButtonText: 'Aceptar'
    });
</script>
<?php endif; ?>

</body>
</html>