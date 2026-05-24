<?php

session_start();
require "../php/db.php";

if (!isset($_POST["usuario"]) || !isset($_POST["password"])) {
    header("Location: ../login.php?error=Datos incompletos");
    return;
}

$usuario = $_POST["usuario"];
$password = $_POST["password"];

// Buscar usuario por email o número de control
$sql = "SELECT id, nombre, apellido_paterno, email, numero_control, password, rol FROM usuarios WHERE (email = '$usuario' OR numero_control = '$usuario') AND estado = 'activo'";
$query = mysqli_query($con, $sql);
$result = mysqli_fetch_array($query);

if ($result && $result['password'] === $password) {
    // Login exitoso
    $_SESSION['usuario_id'] = $result['id'];
    $_SESSION['nombre'] = $result['nombre'];
    $_SESSION['apellido_paterno'] = $result['apellido_paterno'];
    $_SESSION['email'] = $result['email'];
    $_SESSION['numero_control'] = $result['numero_control'];
    $_SESSION['rol'] = $result['rol'];

    // Redirigir según el rol
    if ($result['rol'] == 'admin') {
        header("Location: ../admin/home.php");
    } elseif ($result['rol'] == 'profesor') {
        header("Location: ../profesor/home.php");
    } else {
        header("Location: ../alumno/home.php");
    }
} else {
    header("Location: ../login.php?error=Usuario o contraseña incorrectos");
    return;
}

?>
