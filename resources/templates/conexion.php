<?php
//Database Configuration
$servidor = "localhost"; 
$usuario = "clienteGA";       
$contraseña = "usuario_GA";      
$nombrebaseDatos = "GestorAlertas"; 

//Create the connection
$conexion = new mysqli($servidor, $usuario, $contraseña, $nombrebaseDatos);

//Check if the connection was successful
if ($conexion->connect_error) {
    die("Error al conectar con la base de datos: " . $conexion->connect_error);
}
?>
