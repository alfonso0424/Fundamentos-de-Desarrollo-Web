<?php

session_start();

// Verificar si existe sesión
if(!isset($_SESSION['usuario_id'])){
    header("Location: ../../login.php?error=Inicia sesión");
    return;
}

// Obtener datos de la sesión
$usuario_id = $_SESSION['usuario_id'];
$nombre = $_SESSION['nombre'];
$apellido_paterno = $_SESSION['apellido_paterno'];
$rol = $_SESSION['rol'];
$numero_control = $_SESSION['numero_control'];

?>
