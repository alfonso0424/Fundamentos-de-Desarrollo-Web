<?php
require "../php/verificar_sesion.php";
require "../php/db.php";

if($rol != 'profesor'){
    header("Location: ../../login.php?error=Acceso denegado");
    return;
}

$sql_grupos = "
    SELECT g.id, m.nombre as materia, m.codigo, g.grupo, g.semestre, g.periodo_academico, COUNT(i.id) as estudiantes
    FROM grupos g
    JOIN materias m ON g.materia_id = m.id
    LEFT JOIN inscripciones i ON g.id = i.grupo_id
    WHERE g.profesor_id = $usuario_id
    GROUP BY g.id
    ORDER BY g.semestre, m.nombre
";
$resultado_grupos = mysqli_query($con, $sql_grupos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesor - Sistema Escolar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

    <nav class="barra">
        <span class="nombre-sistema">
            <i class="fas fa-chalkboard-user"></i> Sistema Escolar - Profesor
        </span>
        <a href="home.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="cambiar_contrasena.php"><i class="fas fa-lock"></i> Cambiar Contraseña</a>
        <a href="../php/logout.php" class="btn-salir"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </nav>

    <div class="caja">
        <div class="formulario">

            <h2>Bienvenido Profesor</h2>
            <p class="texto-descripcion">Eres: <?php echo $nombre . " " . $apellido_paterno; ?></p>

            <h3 class="titulopaso">
                <i class="fas fa-book"></i> Tus Grupos
            </h3>

            <?php if(mysqli_num_rows($resultado_grupos) > 0): ?>

                <?php while($grupo = mysqli_fetch_array($resultado_grupos)): ?>
                    <form method="POST" action="calificaciones.php" style="margin-bottom: 15px;">
                        <input type="hidden" name="grupo_id" value="<?php echo $grupo['id']; ?>">
                        <button type="submit" 
                        onmouseover="this.style.boxShadow='0 4px 12px black'; this.style.transform='translateX(5px)';"
                        onmouseout="this.style.boxShadow='0 2px 8px black'; this.style.transform='translateX(0)';">

                            <div style="font-size: 16px; font-weight: bold; color: darkblue; margin-bottom: 10px;">
                                <?php echo $grupo['materia']; ?> - Grupo <?php echo $grupo['grupo']; ?>
                            </div>

                            <div>
                                <span><i class="fas fa-code"></i> <?php echo $grupo['codigo']; ?></span>
                                <span><i class="fas fa-graduation-cap"></i> Semestre <?php echo $grupo['semestre']; ?></span>
                                <span><i class="fas fa-users"></i> <?php echo $grupo['estudiantes']; ?> estudiante<?php echo $grupo['estudiantes'] != 1 ? 's' : ''; ?></span>
                                <span><i class="fas fa-calendar"></i> <?php echo $grupo['periodo_academico']; ?></span>
                            </div>

                        </button>
                    </form>
                <?php endwhile; ?>

            <?php else: ?>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>