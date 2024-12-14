<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    header("Location: login-page.php");
    exit();
}

// Variable para controlar la visibilidad de elementos
$esAdmin = ($_SESSION['rol'] === 'administrador');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Alerta</title>

    <!-- Link to CSS-->
    <link rel="stylesheet" href="/assets/css/common.css">
    <link rel="stylesheet" href="/assets/css/proyectos.css">

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
            <a class="navbar-brand" href="common.html" id="nav_logoCommon">
                <img src="/assets/media/logo.png" alt="Logo">
            </a>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="alerta-cliente.html">Alertas por cliente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notificaciones.html">Notificaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="proyectos.html">Proyectos</a>
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
                <a class="nav-link" href="#" id="nav_miperfil">Mi perfil</a>
            </div>
        </div>
    </nav>

    <section>
        <!-- Container -->
        <div class="container">

            <!--Search-section bar -->
            <div class="search-section">
                <div class="container">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Buscar">
                        </div>
                        <div class="col-md-3">
                            <button class="btn-search ">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Buscar
                            </button>
                        </div>
                        <?php if ($esAdmin): ?>
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <a href="proyectos-registro_p1.html">
                                <button class="btn-search ">
                                    <i class="fa-solid fa-pen-to-square fa-1x icon-spacing"></i>
                                    Registrar nuevo proyecto
                                </button>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tabla de Datos -->
            <div class="container mt-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Proyecto</th>
                                <th>Cliente</th>
                                <th>Vendedor</th>
                                <th>Gestor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td>
                                <div>
                                    <a href="proyectos.detalle.html" class="d-flex flex-column align-items-center text-center no-link">
                                        <i class="fa-solid fa-up-right-from-square fa-2x icon-spacing"></i>
                                        Abrir
                                    </a>
                                    <br>
                                    <a href="proyectos-asignar_alertas.html" class="d-flex flex-column align-items-center text-center no-link">
                                        <i class="fa-solid fa-circle-plus fa-2x icon-spacing"></i>
                                        Relacionar alertas
                                    </a>
                                </div>
                            </td>
                            <td>PRY-CEO-000342</td>
                            <td>Ministerio de Educación Pública</td>
                            <td>Carmen Fonseca Espinoza</td>
                            <td>Esther Murillo Calderón<nav></nav>
                            </td>
                            </tr>
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