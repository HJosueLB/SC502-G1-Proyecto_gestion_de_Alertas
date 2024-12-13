<?php
// Configuración de la base de datos
$servidor = "localhost"; // Dirección del servidor (localhost para desarrollo local)
$usuario = "clienteGA";       // Usuario de la base de datos
$contraseña = "usuario_GA";        // Contraseña del usuario
$baseDatos = "GestorAlertas"; // Nombre de la base de datos

// Crear la conexión
$conexion = new mysqli($servidor, $usuario, $contraseña, $baseDatos);

// Verificar si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Error al conectar con la base de datos: " . $conexion->connect_error);
}
?>
