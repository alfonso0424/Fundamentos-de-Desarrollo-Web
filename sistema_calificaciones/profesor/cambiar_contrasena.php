<?php
require "../php/verificar_sesion.php";
require "../php/db.php";

if($rol != 'alumno' && $rol != 'profesor'){
    header("Location: ../../login.php?error=Acceso denegado");
    return;
}

$mensaje = "";
$tipo_mensaje = "";

// Cambiar contraseña
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $contrasena_actual = $_POST['contrasena_actual'];
    $contrasena_nueva = $_POST['contrasena_nueva'];
    $contrasena_confirmar = $_POST['contrasena_confirmar'];

    if($contrasena_nueva !== $contrasena_confirmar){
        $mensaje = "Las contraseñas nuevas no coinciden";
        $tipo_mensaje = "error";
    } 
    else if($contrasena_nueva === $contrasena_actual){
        $mensaje = "La nueva contraseña debe ser diferente";
        $tipo_mensaje = "error";
    }
    else if(strlen($contrasena_nueva) < 6){
        $mensaje = "La contraseña debe tener al menos 6 caracteres";
        $tipo_mensaje = "error";
    }
    else {
        $sql = "SELECT password FROM usuarios WHERE id = $usuario_id";
        $resultado = mysqli_query($con, $sql);
        $usuario = mysqli_fetch_array($resultado);

        if($usuario['password'] === $contrasena_actual){
            $sql_update = "UPDATE usuarios SET password = '$contrasena_nueva' WHERE id = $usuario_id";
            
            if(mysqli_query($con, $sql_update)){
                $mensaje = "Contraseña cambiad exitosamente";
                $tipo_mensaje = "exito";
            } else {
                $mensaje = "Error al cambiar la contraseña";
                $tipo_mensaje = "error";
            }
        } else {
            $mensaje = "La contraseña actual es incorrecta";
            $tipo_mensaje = "error";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo.css">
    <style>

    </style>
</head>
<body>

    <nav class="barra">
        <span class="nombre-sistema">
            <?php 
                if($rol == 'alumno'){
                    echo 'Sistema Escolar - Alumno';
                } else {
                    echo 'Sistema Escolar - Profesor';
                }
            ?>
        </span>
        <?php if($rol == 'alumno'): ?>
            <a href="home.php"><i class="fas fa-home"></i> Inicio</a>
            <a href="mi_kardex.php"><i class="fas fa-list"></i> Mi Kardex</a>
        <?php else: ?>
            <a href="home.php"><i class="fas fa-home"></i> Inicio</a>
        <?php endif; ?>
        <a href="cambiar_contrasena.php" style="background-color: rgba(255,255,255,0.2);"><i class="fas fa-lock"></i> Cambiar Contraseña</a>
        <a href="../php/logout.php" class="btn-salir"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </nav>

    <div class="caja">
        <div class="formulario">

            <h2><i class="fas fa-lock"></i> Cambiar Contraseña</h2>
            <p class="texto-descripcion">Actualiza tu contraseña de acceso al sistema</p>

            <?php if($mensaje): ?>
                <div class="<?php echo $tipo_mensaje == 'exito' ? 'info-mensaje' : 'error-mensaje'; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="campo">
                    <label><i class="fas fa-key"></i> Contraseña Actual</label>
                    <input type="password" name="contrasena_actual" required>
                </div>

                <div class="campo">
                    <label><i class="fas fa-lock"></i> Nueva Contraseña</label>
                    <input type="password" name="contrasena_nueva">
                </div>

                <div class="campo">
                    <label><i class="fas fa-lock"></i> Confirmar Nueva Contraseña</label>
                    <input type="password" name="contrasena_confirmar" required minlength="6">
                </div>

                <button class="btn-guardar" type="submit">
                    <i class="fas fa-check"></i> Cambiar Contraseña
                </button>

            </form>


        </div>
    </div>

</body>
</html>