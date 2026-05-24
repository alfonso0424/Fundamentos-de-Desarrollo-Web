<?php
require "../php/verificar_sesion.php";
require "../php/db.php";

if($rol != 'alumno'){
    header("Location: ../../login.php?error=Acceso denegado");
    return;
}

// Obtener calificaciones 
$sql = "SELECT m.nombre as materia, c.calificacion 
        FROM calificaciones c 
        JOIN materias m ON c.materia_id = m.id 
        WHERE c.usuario_id = $usuario_id
        ORDER BY m.nombre";

$resultado = mysqli_query($con, $sql);

// Calcular promedio general
$sql_promedio = "SELECT AVG(calificacion) as promedio FROM calificaciones WHERE usuario_id = $usuario_id";
$resultado_promedio = mysqli_query($con, $sql_promedio);
$fila_promedio = mysqli_fetch_array($resultado_promedio);
$promedio = $fila_promedio['promedio'] ? round($fila_promedio['promedio'], 2) : 0;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Kardex</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

    <nav class="barra">
        <span class="nombre-sistema">Sistema Escolar</span>
        <a href="home.php">Inicio</a>
        <a href="mi_kardex.php">Mi Kardex</a>
        <a href="../php/logout.php" class="btn-salir">Cerrar sesión</a>
    </nav>

    <div class="caja">
        <div class="formulario">

            <h2>Mi Kardex</h2>
            <p class="texto-descripcion">Aquí puedes ver todas tus calificaciones.</p>

            <div class="datosalumno">
                <div class="fila-doble">
                    <div class="dato-individual">
                        <span class="etiqueta-dato">Nombre</span>
                        <span><?php echo $nombre . " " . $apellido_paterno; ?></span>
                    </div>
                    <div class="dato-individual">
                        <span class="etiqueta-dato">Número de control</span>
                        <span><?php echo $numero_control; ?></span>
                    </div>
                </div>
                <div class="fila-doble">
                    <div class="dato-individual">
                        <span class="etiqueta-dato">Promedio general</span>
                        <span><?php echo $promedio; ?></span>
                    </div>
                </div>
            </div>

            <?php if(mysqli_num_rows($resultado) > 0): ?>
                <table>
                    <tr>
                        <th>Materia</th>
                        <th>Calificación</th>
                    </tr>
                    <?php while($fila = mysqli_fetch_array($resultado)): ?>
                        <tr>
                            <td><?php echo $fila['materia']; ?></td>
                            <td><?php echo $fila['calificacion']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <div class="info-mensaje">No hay calificaciones registradas aún.</div>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>