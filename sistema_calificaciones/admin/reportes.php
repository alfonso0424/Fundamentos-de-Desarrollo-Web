<?php
require "../php/verificar_sesion.php";
require "../php/db.php";

if($rol != 'admin'){
    header("Location: ../../login.php?error=Acceso denegado");
    return;
}

// Promedio general
$sql_promedio_general = "SELECT AVG(calificacion) as promedio_general FROM calificaciones";
$resultado_promedio_general = mysqli_query($con, $sql_promedio_general);
$promedio_general = mysqli_fetch_array($resultado_promedio_general);

// Promedio por carrera
$sql_promedio_carrera = "
    SELECT u.carrera, AVG(c.calificacion) as promedio
    FROM calificaciones c
    JOIN usuarios u ON c.usuario_id = u.id
    WHERE u.rol = 'alumno'
    GROUP BY u.carrera
    ORDER BY promedio DESC
";
$resultado_promedio_carrera = mysqli_query($con, $sql_promedio_carrera);

// Total de alumnos
$sql_total_alumnos = "SELECT COUNT(*) as total FROM usuarios WHERE rol = 'alumno'";
$resultado_total_alumnos = mysqli_query($con, $sql_total_alumnos);
$total_alumnos = mysqli_fetch_array($resultado_total_alumnos);

// Total de calificaciones
$sql_total_calificaciones = "SELECT COUNT(*) as total FROM calificaciones";
$resultado_total_calificaciones = mysqli_query($con, $sql_total_calificaciones);
$total_calificaciones = mysqli_fetch_array($resultado_total_calificaciones);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

    <nav class="barra">
        <span class="nombre-sistema">Sistema Escolar</span>
        <a href="home.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="inscribir_alumnos.php"><i class="fas fa-user-plus"></i> Inscribir</a>
        <a href="gestionar_materias.php"><i class="fas fa-book"></i> Materias</a>
        <a href="reportes.php" style="background-color: rgba(255,255,255,0.2);"><i class="fas fa-chart-bar"></i> Reportes</a>
        <a href="../php/logout.php" class="btn-salir"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </nav>

    <div class="caja">
        <div class="formulario">

            <h2><i class="fas fa-chart-bar"></i> Reportes del Sistema</h2>

            <div class="estadisticas">
                
                <div class="card">
                    <div class="valor"><?php echo round($promedio_general['promedio_general'], 2); ?></div>
                    <div class="etiqueta"><i class="fas fa-star"></i> Promedio General</div>
                </div>

                <div class="card">
                    <div class="valor"><?php echo $total_alumnos['total']; ?></div>
                    <div class="etiqueta"><i class="fas fa-users"></i> Alumnos Registrados</div>
                </div>

                <div class="card">
                    <div class="valor"><?php echo $total_calificaciones['total']; ?></div>
                    <div class="etiqueta"><i class="fas fa-graduation-cap"></i> Calificaciones</div>
                </div>

            </div>

            <h3 class="titulopaso"><i class="fas fa-chart-line"></i> Promedio por Carrera</h3>

            <?php if(mysqli_num_rows($resultado_promedio_carrera) > 0): ?>
                <table>
                    <tr>
                        <th>Carrera</th>
                        <th>Promedio</th>
                    </tr>
                    <?php while($carrera = mysqli_fetch_array($resultado_promedio_carrera)): ?>
                        <tr>
                            <td><?php echo $carrera['carrera']; ?></td>
                            <td><strong><?php echo round($carrera['promedio'], 2); ?></strong></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
            <?php endif; ?>

        </div>
    </div>

    <style>
        .estadisticas {
            display: flex;
            gap: 20px;
            margin: 30px 0;
        }

        .card .valor {
            font-size: 36px;
            font-weight: bold;
            color: darkblue;
            margin-bottom: 10px;
        }

        .card .etiqueta {
            color: gray;
            font-size: 14px;
        }
    </style>

</body>
</html>