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

// Function to get options from a table
function obtenerOpciones($conexion, $tabla, $id, $nombre) {
    $query = "SELECT $id, $nombre FROM $tabla";
    $stmt = $conexion->prepare($query);
    if (!$stmt) {
        die("Error en la consulta: " . $conexion->error . " - Consulta: $query");
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $options = [];
    while ($row = $result->fetch_assoc()) {
        $options[] = $row;
    }
    return $options;
}

// Get options for dropdowns
$alertas = obtenerOpciones($conexion, 'gestoralertas.diccionarioalertas', 'id_AlertaDiccionario', 'nombre');
$clientes = obtenerOpciones($conexion, 'gestoralertas.proyectos', 'id_Proyecto', 'nombreCliente');
$medios = obtenerOpciones($conexion, 'gestoralertas.medioNotificacion', 'id_Medio', 'nombre'); // Corregido aquí

$successMessage = $errorMessage = '';

// Process the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_Alerta = $_POST['id_Alerta'];
    $id_cliente = $_POST['id_cliente'];
    $id_Medio = $_POST['id_Medio'];
    $comentarios = $_POST['comentarios'];
    $analista = $_POST['analista'];
    $descripcion = $_POST['descripcion'];
    $tiquete = $_POST['tiquete'];

    $stmt = $conexion->prepare("CALL sp_agregar_notificacion(?, ?, ?, ?, ?, '', '', ?, ?)");
    if (!$stmt) {
        $errorMessage = "Error en la consulta: " . $conexion->error;
    } else {
        $stmt->bind_param("iiissss", $id_Alerta, $id_Medio, $id_cliente, $comentarios, $analista, $descripcion, $tiquete);
        if ($stmt->execute()) {
            $successMessage = "Notificación registrada exitosamente.";
        } else {
            $errorMessage = "Error al registrar la notificación: " . $stmt->error;
        }
    }
}
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
            <h2>Registrar Nueva Notificación</h2>

            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?= $successMessage ?></div>
            <?php elseif ($errorMessage): ?>
                <div class="alert alert-danger"><?= $errorMessage ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="id_Alerta" class="form-label">Alerta</label>
                    <select class="form-select" id="id_Alerta" name="id_Alerta" required>
                        <option value="" disabled selected>Seleccione una alerta</option>
                        <?php foreach ($alertas as $alerta): ?>
                            <option value="<?= $alerta['id_AlertaDiccionario'] ?>"><?= htmlspecialchars($alerta['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_cliente" class="form-label">Cliente</label>
                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                        <option value="" disabled selected>Seleccione un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id_Proyecto'] ?>"><?= htmlspecialchars($cliente['nombreCliente']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_Medio" class="form-label">Medio</label>
                    <select class="form-select" id="id_Medio" name="id_Medio" required>
                        <option value="" disabled selected>Seleccione un medio</option>
                        <?php foreach ($medios as $medio): ?>
                            <option value="<?= $medio['id_Medio'] ?>"><?= htmlspecialchars($medio['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="comentarios" class="form-label">Comentarios</label>
                    <input type="text" class="form-control" id="comentarios" name="comentarios" required>
                </div>

                <div class="mb-3">
                    <label for="analista" class="form-label">Analista</label>
                    <input type="text" class="form-control" id="analista" name="analista" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="tiquete" class="form-label">Tiquete Speed-e</label>
                    <input type="text" class="form-control" id="tiquete" name="tiquete">
                </div>

                <button type="submit" class="btn btn-primary">Registrar</button>
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