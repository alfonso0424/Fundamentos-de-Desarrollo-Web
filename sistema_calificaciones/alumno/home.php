<?php
require "../php/verificar_sesion.php";

if($rol != 'alumno'){
    header("Location: ../../login.php?error=Acceso denegado");
    return;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumno - Sistema Escolar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        .tarjetas-container {
            display: grid;
            gap: 25px;
            margin-top: 30px;
        }

        .tarjeta {
            background: white;
            border-radius: 8px;
            padding: 30px;
            text-decoration: none;
            color: inherit;
            border-top: 4px solid darkblue;
            transition: all 0.3s ease;
            cursor: pointer;
            text-align: center;
        }

        .tarjeta:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .tarjeta-icono {
            font-size: 48px;
            color: darkblue;
            margin-bottom: 15px;
        }

        .tarjeta-titulo {
            font-size: 18px;
            font-weight: bold;
            color: darkblue;
            margin-bottom: 10px;
        }

        .tarjeta-descripcion {
            font-size: 14px;
            color: #666;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <nav class="barra">
        <span class="nombre-sistema">Sistema Escolar - Alumno</span>
        <a href="home.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="mi_kardex.php"><i class="fas fa-list"></i> Mi Kardex</a>
        <a href="cambiar_contrasena.php"><i class="fas fa-lock"></i> Cambiar Contraseña</a>
        <a href="../php/logout.php" class="btn-salir"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </nav>

    <div class="caja">
        <div class="formulario">

            <h2>Bienvenido Alumno</h2>
            <p class="texto-descripcion">Eres: <?php echo $nombre . " " . $apellido_paterno; ?></p>

            <h3 class="titulopaso">Acciones disponibles:</h3>
            
            <div class="tarjetas-container">
                
                <a href="mi_kardex.php" class="tarjeta">
                    <div class="tarjeta-icono">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div class="tarjeta-titulo">Mi Kardex</div>
                    <div class="tarjeta-descripcion">Consulta todas tus calificaciones por período</div>
                </a>

            </div>

        </div>
    </div>

</body>
</html>