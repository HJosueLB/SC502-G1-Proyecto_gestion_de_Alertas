<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar nuevo proyecto</title>

    <!-- Link to CSS-->
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/common.css">
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/proyectos-registro_p1.css">

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

            <!-- Título con el botón alineado a la derecha -->
            <div class="form-header">
                <h2 class="form-title">Registrar nuevo proyecto:</h2>
                <div class="button-container">
                    <a href="proyectos-registro_p2.php">
                    <button class="button">Guardar</button>
                    </a>
                    <a href="proyectos.php">
                        <button class="button">Regresar</button>
                    </a>
                </div>
            </div>

            <div class="form-grid">
                <!-- Primera columna -->
                <div>
                    <div class="form-group">
                        <label for="cliente">Nombre del cliente:</label>
                        <input type="text" id="cliente" name="cliente">
                    </div>
                    <div class="form-group">
                        <label for="proyecto">Nombre del proyecto:</label>
                        <input type="text" id="proyecto" name="proyecto">
                    </div>
                    <div class="form-group">
                        <label for="fechaInicio">Fecha de inicio:</label>
                        <input type="date" id="fechaInicio" name="fechaInicio">
                    </div>
                    <div class="form-group">
                        <label for="fechaFin">Fecha de fin:</label>
                        <input type="date" id="fechaFin" name="fechaFin">
                    </div>
                    <div class="form-group">
                        <label for="tipoContrato">Tipo de contrato:</label>
                        <input type="text" id="tipoContrato" name="tipoContrato">
                    </div>
                    <div class="form-group">
                        <label for="vendedorCuenta">Vendedor de la cuenta:</label>
                        <input type="text" id="vendedorCuenta" name="vendedorCuenta">
                    </div>
                    <div class="form-group">
                        <label for="gestorContrato">Gestor de contrato:</label>
                        <input type="text" id="gestorContrato" name="gestorContrato">
                    </div>
                </div>

                <!-- Segunda columna -->
                <div>
                    <div class="form-group">
                        <label for="unidadNegocio">Unidad de negocio:</label>
                        <input type="text" id="unidadNegocio" name="unidadNegocio">
                    </div>
                    <div class="form-group">
                        <label for="cobrosMensuales">Cobros mensuales:</label>
                        <input type="text" id="cobrosMensuales" name="cobrosMensuales">
                    </div>
                    <div class="form-group">
                        <label for="servicioContratado">Servicio contratado:</label>
                        <input type="text" id="servicioContratado" name="servicioContratado">
                    </div>
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