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

// The procedure is called and executed
function obtenerAlertasFiltradas($conexion) {
    $sql = "SELECT * FROM vista_alertas_cliente";
    $result = $conexion->query($sql);

    if (!$result) {
        error_log("SQL Query Error: " . $conexion->error);
        return [];
    }

    $alertas = [];
    while ($row = $result->fetch_assoc()) {
        $alertas[] = $row;
    }

    return $alertas;
}

// Fetch alerts
$alertas = obtenerAlertasFiltradas($conexion);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Alerta</title>

    <!-- Link to CSS-->
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/common.css">
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/alerta-cliente.css">

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
                    </li>}
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
        <!-- Container -->
        <div class="container">

            <!--Search-section bar -->
            <div class="search-section">
                <div class="container">
                    <div class="row g-3 align-items-center justify-content-between">
                        <div class="col-md-6">
                            <label class="form-label">Nombre de la alerta:</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                placeholder="Buscar por nombre de alerta"
                                id="buscar-nombre-alerta"
                                onkeyup="filtrarPorNombre(this.value)"
                            >
                        </div>
                        <?php if ($esAdmin): ?>
                        <div class="col-md-6 d-flex justify-content-end">
                            <button class="btn-register" onclick="window.location.href='alerta-registrar.php';">
                                Registrar Alerta
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="container mt-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre de la Alerta</th>
                                <th>Descripción de la Alerta</th>
                                <th>Criticidad</th>
                                <th>Cliente</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-alertas">
                            <?php foreach ($alertas as $alerta): ?>
                                <tr>
                                    <td>
                                        <a href="alerta-detalle.php?id=<?php echo htmlspecialchars($alerta['ID_Alerta']); ?>"
                                        class="d-flex flex-column align-items-center text-center no-link">
                                            <i class="fa-solid fa-up-right-from-square fa-2x icon-spacing" style="color: #000000"></i>
                                            Abrir
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($alerta['Nombre_Alerta']); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['Descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['Criticidad']); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['Nombre_Cliente']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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

<script>
function filtrarPorNombre(valor) {
    const filas = document.querySelectorAll('#tabla-alertas tr');
    filas.forEach(fila => {
        const nombreAlerta = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
        if (nombreAlerta.includes(valor.toLowerCase())) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}
</script>
