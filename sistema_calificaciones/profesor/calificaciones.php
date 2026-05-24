<?php
require "../php/verificar_sesion.php";
require "../php/db.php";

if($rol != 'profesor'){
    header("Location: ../../login.php?error=Acceso denegado");
    return;
}

$mensaje = "";

$grupo_id = null;
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['grupo_id'])){
    $grupo_id = $_POST['grupo_id'];
} 

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'guardar'){
    $grupo_id = $_POST['grupo_id'];
    $materia_id = $_POST['materia_id'];
    $periodo = $_POST['periodo'];

    if(isset($_POST['calificaciones']))
    {
        $actualizadas = 0;
        foreach($_POST['calificaciones'] as $usuario_id => $calificacion)
        {
            if($calificacion == "") continue;
            if(!is_numeric($calificacion) || $calificacion < 0 || $calificacion > 100) continue;

            $sql_existe = "SELECT id FROM calificaciones 
                           WHERE usuario_id=$usuario_id AND materia_id=$materia_id AND grupo_id=$grupo_id AND periodo_academico='$periodo'";
            $resultado_existe = mysqli_query($con, $sql_existe);

            if(mysqli_num_rows($resultado_existe) > 0)
            {
                $sql = "UPDATE calificaciones SET calificacion=$calificacion 
                        WHERE usuario_id=$usuario_id AND materia_id=$materia_id AND grupo_id=$grupo_id AND periodo_academico='$periodo'";
            } else {
                $sql = "INSERT INTO calificaciones (usuario_id, materia_id, grupo_id, periodo_academico, calificacion) 
                        VALUES ($usuario_id, $materia_id, $grupo_id, '$periodo', $calificacion)";
            }

            if(mysqli_query($con, $sql)) $actualizadas++;
        }
        $mensaje = "Guardado " ;
    }
}

$grupo_info = null;
$alumnos = null;

if($grupo_id){
    $sql_grupo = "SELECT g.*, m.nombre as materia, m.id as materia_id, m.codigo
                  FROM grupos g
                  JOIN materias m ON g.materia_id = m.id
                  WHERE g.id = $grupo_id AND g.profesor_id = $usuario_id";
    $resultado_grupo = mysqli_query($con, $sql_grupo);
    $grupo_info = mysqli_fetch_array($resultado_grupo);

    if($grupo_info){
        $sql_alumnos = "
            SELECT u.id, u.nombre, u.apellido_paterno, u.numero_control,
                   COALESCE(c.calificacion, '') as calificacion
            FROM inscripciones i
            JOIN usuarios u ON i.usuario_id = u.id
            LEFT JOIN calificaciones c ON u.id = c.usuario_id 
                AND c.materia_id = {$grupo_info['materia_id']} 
                AND c.grupo_id = $grupo_id
            WHERE i.grupo_id = $grupo_id
            ORDER BY u.nombre
        ";
        $alumnos = mysqli_query($con, $sql_alumnos);
    }
}

$sql_grupos = "
    SELECT g.id, m.nombre as materia, g.grupo
    FROM grupos g
    JOIN materias m ON g.materia_id = m.id
    WHERE g.profesor_id = $usuario_id
    ORDER BY m.nombre
";
$resultado_grupos = mysqli_query($con, $sql_grupos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificaciones</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

    <nav class="barra">
        <span class="nombre-sistema">
            <i class="fas fa-chalkboard-user"></i> Sistema Escolar
        </span>
        <a href="home.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="cambiar_contrasena.php"><i class="fas fa-lock"></i> Cambiar Contraseña</a>
        <a href="../php/logout.php" class="btn-salir"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </nav>

    <div class="caja">
        <div class="formulario">

            <h2><i class="fas fa-star"></i> Calificaciones</h2>
            <p class="texto-descripcion">Asigna y edita calificaciones</p>

            <?php if($mensaje): ?>
                <div class="info-mensaje"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <div class="campo">
                <label>Selecciona un grupo</label>
                <form method="POST">
                    <select name="grupo_id" onchange="this.form.submit()">
                        <option value="">-- Elige un grupo --</option>
                        <?php while($g = mysqli_fetch_array($resultado_grupos)): ?>
                            <option value="<?php echo $g['id']; ?>" <?php echo $grupo_id == $g['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($g['materia']) . " - Grupo " . $g['grupo']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </form>
            </div>

            <?php if($grupo_info && $alumnos && mysqli_num_rows($alumnos) > 0): ?>

                <div class="datosalumno" style="margin-top: 20px;">
                    <div class="fila-doble">
                        <div class="dato-individual">
                            <span class="etiqueta-dato">Materia</span>
                            <span><?php echo htmlspecialchars($grupo_info['materia']); ?></span>
                        </div>
                        <div class="dato-individual">
                            <span class="etiqueta-dato">Grupo</span>
                            <span><?php echo $grupo_info['grupo']; ?></span>
                        </div>
                    </div>
                </div>

                <form method="POST" style="margin-top: 20px;">
                    <input type="hidden" name="accion" value="guardar">
                    <input type="hidden" name="grupo_id" value="<?php echo $grupo_id; ?>">
                    <input type="hidden" name="materia_id" value="<?php echo $grupo_info['materia_id']; ?>">

                    <div class="campo">
                        <label>Período</label>
                        <select name="periodo" required>
                            <option value="Agosto-Diciembre 2026">Agosto - Diciembre 2026</option>
                        </select>
                    </div>

                    <h3 class="titulopaso">Calificaciones</h3>

                    <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
                        <tr style="background-color: #1a237e; color: white;">
                            <th>Alumno</th>
                            <th>Actual</th>
                            <th>Nueva</th>
                        </tr>
                        <?php while($alumno = mysqli_fetch_array($alumnos)): ?>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px;">
                                    <?php echo htmlspecialchars($alumno['numero_control'] . " - " . $alumno['nombre'] . " " . $alumno['apellido_paterno']); ?>
                                </td>
                                <td>
                                    <?php echo $alumno['calificacion'] ?: "SIN CALIF"; ?>
                                </td>
                                <td>
                                    <input type="number"
                                           name="calificaciones[<?php echo $alumno['id']; ?>]"
                                           min="0" max="100" step="0.5"
                                           placeholder="Ingresa aquí"
                                           style="width: 100px; padding: 8px; border: 2px solid #1976d2; border-radius: 4px; font-size: 13px;">
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>

                    <button class="btn-guardar" type="submit">
                        <i class="fas fa-save"></i> Guardar Calificaciones
                    </button>
                </form>

            <?php elseif($grupo_id): ?>
                <div class="info-mensaje" style="margin-top: 20px;">
                    No hay alumnos en este grupo
                </div>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>
