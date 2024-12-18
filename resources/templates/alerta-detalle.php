<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    header("Location: login-page.php");
    exit();
}

// Variable to control the visibility of elements
$esAdmin = ($_SESSION['rol'] === 'administrador');


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

// The procedure is called and executed
function obtenerDetalleAlerta($conexion, $idAlerta) {
    // Prepare the call to the stored procedure
    $stmt = $conexion->prepare("CALL P_ObtenerAlerta(?)");
    $stmt->bind_param("i", $idAlerta);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc(); // Return the first row as an associative array
    } else {
        return null; // No data found
    }
}

// Fetch the alert details
$detalleAlerta = obtenerDetalleAlerta($conexion, $idAlerta);

// If no data is found, show an error
if (!$detalleAlerta) {
    die("Error: Alert not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $stmt = $conexion->prepare("CALL P_EliminarAlerta(?)");
    $stmt->bind_param("i", $idAlerta);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Alerta eliminada correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar la alerta.']);
    }
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Alerta</title>

    <!-- Link to CSS-->
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/common.css">
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/alerta-detalle.css">

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
            <div class="button-back-container">
                <button id="backButton" class="button-back" 
                    onclick="window.location.href='alerta-cliente.php'">
                    Regresar
                </button>
            </div>
            <div class="alert-header">
                <h2><?php echo htmlspecialchars($detalleAlerta['Nombre_Alerta']); ?></h2>
                <p class="criticality">
                    <span>Criticidad:</span> 
                    <strong><?php echo htmlspecialchars($detalleAlerta['Criticidad']); ?></strong>
                </p>
            </div>
            <div class="alert-card">
                <table class="contact-table">
                    <thead>
                        <tr>
                            <th>Medio de notificación</th>
                            <th>Horario</th>
                            <th>Sistema</th>
                            <th>Servicio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($detalleAlerta['Medio_Notificacion'] ?? 'No definido'); ?></td>
                            <td><?php echo htmlspecialchars($detalleAlerta['Horario'] ?? 'No definido'); ?></td>
                            <td><?php echo htmlspecialchars($detalleAlerta['Sistema'] ?? 'No definido'); ?></td>
                            <td><?php echo htmlspecialchars($detalleAlerta['Servicio'] ?? 'No definido'); ?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="description-box">
                    <h3>Descripción</h3>
                    <p><?php echo htmlspecialchars($detalleAlerta['Descripcion']); ?></p>
                </div>
            </div>
            <?php if ($esAdmin): ?>
            <div class="button-container">
                <p></p>
                <button id="deleteButton" class="button-back">Eliminar</button>
                <button id="editButton" class="button-back" 
                    onclick="window.location.href='alerta-editar.php?id=<?php echo htmlspecialchars($detalleAlerta['ID_Alerta']); ?>'">
                    Editar
                </button>
            </div>
            <?php endif; ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        document.getElementById('deleteButton').addEventListener('click', function () {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esta acción.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(window.location.href, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'delete=true'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('Eliminado!', data.message, 'success').then(() => {
                            window.location.href = 'alerta-cliente.php';
                        });
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Error!', 'Hubo un problema con la eliminación.', 'error'));
            }
        });
    });

    </script>

</body>

</html>
