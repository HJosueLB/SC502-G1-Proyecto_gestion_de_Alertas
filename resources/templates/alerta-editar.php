<?php
session_start();

// Variable to control the visibility of elements
$esAdmin = ($_SESSION['rol'] === 'administrador');


// Check if the user is authenticated
if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    header("Location: login-page.php");
    exit();
}

// Include the database connection file
require_once 'conexion.php'; // Adjust the path if needed

// Validate the connection
if (!isset($conexion)) {
    die("Error: Database connection is not defined.");
}

// Validate the ID from the request
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid alert ID.");
}

// Get the alert ID
$idAlerta = intval($_GET['id']);

function obtenerDetalleAlerta($conexion, $idAlerta) {
    $stmt = $conexion->prepare("CALL P_ObtenerAlerta(?)");
    $stmt->bind_param("i", $idAlerta);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Validate the connection
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

// Fetch the alert details
$detalleAlerta = obtenerDetalleAlerta($conexion, $idAlerta);

if (!$detalleAlerta) {
    die("Error: Alert not found.");
}

// Fetch dropdown options
$criticidades = obtenerOpcionesDropdown($conexion, 'criticidad');
$horarios = obtenerOpcionesDropdown($conexion, 'horario');
$medios = obtenerOpcionesDropdown($conexion, 'medioNotificacion');
$clientes = obtenerOpcionesDropdown($conexion, 'proyectos');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreAlerta = $conexion->real_escape_string($_POST['nombreAlerta']);
    $id_Criticidad = intval($_POST['id_Criticidad']);
    $id_Horario = intval($_POST['id_Horario']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);
    $id_Medio = intval($_POST['id_Medio']);
    $id_Proyecto = intval($_POST['id_Proyecto']);

    $sql = "CALL P_EditarAlertaCliente(
                $idAlerta, 
                '$nombreAlerta', 
                $id_Criticidad, 
                $id_Horario, 
                '$descripcion', 
                $id_Medio, 
                $id_Proyecto
            )";

    if ($conexion->query($sql)) {
        $successMessage = true; 
    } else {
        $errorMessage = 'No se pudo actualizar la alerta. Por favor, inténtalo de nuevo.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alerta</title>

    <!-- Link to CSS-->
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/common.css">
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/alerta-editar.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <!-- Navbar -->
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
            <h2 id="titleModule">Editar Alerta</h2>

            <form id="editForm" method="POST" action="">

                <div class="mb-3">
                    <label for="nombreAlerta" class="form-label">Nombre de la Alerta</label>
                    <input type="text" class="form-control" id="nombreAlerta" name="nombreAlerta"
                        value="<?php echo htmlspecialchars($detalleAlerta['Nombre_Alerta']); ?>" required readonly />
                </div>
                <div class="mb-3">
                    <label for="id_Criticidad" class="form-label">Criticidad</label>
                    <select class="form-select" id="id_Criticidad" name="id_Criticidad" required>
                        <?php foreach ($criticidades as $criticidad) : ?>
                        <option value="<?php echo $criticidad['idCriticidad']; ?>" <?php echo
                            $detalleAlerta['Criticidad']===$criticidad['nivel'] ? 'selected' : '' ; ?>>
                            <?php echo htmlspecialchars($criticidad['nivel']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_Horario" class="form-label">Horario</label>
                    <select class="form-select" id="id_Horario" name="id_Horario" required>
                        <?php foreach ($horarios as $horario) : ?>
                        <option value="<?php echo $horario['id_Horario']; ?>" <?php echo
                            $detalleAlerta['Horario']===$horario['nombre'] ? 'selected' : '' ; ?>>
                            <?php echo htmlspecialchars($horario['nombre']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                        required><?php echo htmlspecialchars($detalleAlerta['Descripcion']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="id_Medio" class="form-label">Medio de Notificación</label>
                    <select class="form-select" id="id_Medio" name="id_Medio" required>
                        <?php foreach ($medios as $medio) : ?>
                        <option value="<?php echo $medio['id_Medio']; ?>" <?php echo
                            $detalleAlerta['Medio_Notificacion']===$medio['nombre'] ? 'selected' : '' ; ?>>
                            <?php echo htmlspecialchars($medio['nombre']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_Proyecto" class="form-label">Cliente</label>
                    <select class="form-select" id="id_Proyecto" name="id_Proyecto" required>
                        <?php foreach ($clientes as $cliente) : ?>
                        <option value="<?php echo $cliente['id_Proyecto']; ?>" <?php echo
                            $detalleAlerta['Nombre_Cliente']===$cliente['nombreCliente'] ? 'selected' : '' ; ?>>
                            <?php echo htmlspecialchars($cliente['nombreCliente']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="button-container">
                    <button type="submit" class="btn btn-primary" id="SaveButton">Guardar Cambios</button>
                    <a href="alerta-cliente.php" class="btn btn-secondary" id="CancelButton">Cancelar</a>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto p-2" id="footer_common">
        <div class="container">
            <div class="col">
                <p class="lead text-center" style="font-size: 1rem;">
                    Derechos Reservados Gestor de alertas - Universidad Fidélitas &COPY; 2024
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($successMessage) && $successMessage === true): ?>
    <script>
        Swal.fire({
            title: '¡Éxito!',
            text: 'Alerta actualizada correctamente.',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = '/SC502-G1-Proyecto_gestion_de_Alertas/resources/templates/alerta-detalle.php?id=<?php echo $idAlerta; ?>';
        });
    </script>
    <?php elseif (isset($errorMessage)): ?>
    <script>
        Swal.fire({
            title: '¡Error!',
            text: '<?php echo htmlspecialchars($errorMessage); ?>',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    </script>
    <?php endif; ?>

    <script>
    document.getElementById('editForm').addEventListener('submit', function (event) {
        event.preventDefault(); 

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se guardarán los cambios realizados en la alerta.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); // Enviar el formulario si se confirma
            }
        });
    });
    </script>


</body>

</html>