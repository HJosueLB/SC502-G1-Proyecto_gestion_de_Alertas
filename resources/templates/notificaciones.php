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

// Get a list of notifications
$query = "SELECT n.id_notificacion, n.fechaNotificacion, n.comentarios, 
                 n.analista, p.nombreCliente AS cliente, d.nombre AS alerta, n.descripcion
          FROM gestoralertas.notificaciones n
          JOIN gestoralertas.diccionarioalertas d ON n.id_Alerta = d.id_AlertaDiccionario
          JOIN gestoralertas.proyectos p ON n.id_cliente = p.id_Proyecto";
$resultado = $conexion->query($query);
$notificaciones = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Alerta</title>

    <!-- Link to CSS-->
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/notificaciones.css">
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
            <h2>Lista de Notificaciones</h2>
                <?php if ($esAdmin): ?>
                <div class="buttons-container">
                    <button class="button-register" id="registrarNotif" onclick="window.location.href='notificaciones-registro.php';">
                        Registrar Notificación
                    </button>
                </div>
                <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Analista</th>
                            <th>Cliente</th>
                            <th>Alerta</th>
                            <th>Descripción</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notificaciones as $n): ?>
                        <tr>
                            <td><?= $n['id_notificacion'] ?></td>
                            <td><?= htmlspecialchars($n['fechaNotificacion']) ?></td>
                            <td><?= htmlspecialchars($n['analista']) ?></td>
                            <td><?= htmlspecialchars($n['cliente']) ?></td>
                            <td><?= htmlspecialchars($n['alerta']) ?></td>
                            <td><?= htmlspecialchars($n['descripcion']) ?></td>
                            <td>
                                <a href="notificaciones-detalle.php?id=<?= $n['id_notificacion'] ?>" class="btn btn-info btn-sm">Ver</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

            <!-- Project's common footer development-->
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