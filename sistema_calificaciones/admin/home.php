<?php
require "../php/verificar_sesion.php";

if($rol != 'admin'){
    header("Location: ../../login.php?error=Acceso denegado");
    return;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Sistema Escolar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        .tarjetas-container {
            display: flex;
            gap: 25px;
            margin-top: 30px;
        }

        .tarjeta {
            background: white;
            border-radius: 8px;
            padding: 30px;
            border-top: 4px solid darkblue;
            transition: all 0.3s ease;
            cursor: pointer;
            text-align: center;
        }

        .tarjeta:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 24px black;
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
            color: gray;
        }
    </style>
</head>
<body>

    <nav class="barra">
        <span class="nombre-sistema">
            <i class="fas fa-shield-alt"></i> Sistema Escolar - Admin
        </span>
        <a href="home.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="inscribir_alumnos.php"><i class="fas fa-user-plus"></i> Inscribir</a>
        <a href="gestionar_materias.php"><i class="fas fa-book"></i> Materias</a>
        <a href="reportes.php"><i class="fas fa-chart-bar"></i> Reportes</a>
        <a href="../php/logout.php" class="btn-salir"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </nav>

    <div class="caja">
        <div class="formulario">

            <h2>Bienvenido Admin</h2>
            <p class="texto-descripcion">Eres: <?php echo $nombre . " " . $apellido_paterno; ?></p>

            <h3 class="titulopaso">Acciones disponibles:</h3>
            
            <div class="tarjetas-container">
                
                <a href="inscribir_alumnos.php" class="tarjeta">
                    <div class="tarjeta-icono">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="tarjeta-titulo">Inscribir Usuarios</div>
                    <div class="tarjeta-descripcion">
                        Agrega alumnos y profesores al sistema
                    </div>
                </a>

                <a href="gestionar_materias.php" class="tarjeta">
                    <div class="tarjeta-icono">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="tarjeta-titulo">Gestionar Materias</div>
                    <div class="tarjeta-descripcion">
                        Crea las materias del sistema
                    </div>
                </a>

                <a href="reportes.php" class="tarjeta">
                    <div class="tarjeta-icono">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="tarjeta-titulo">Reportes</div>
                    <div class="tarjeta-descripcion">
                        Ver promedios generales y por carrera
                    </div>
                </a>

            </div>

        </div>
    </div>

</body>
</html>
