<?php
require "../php/verificar_sesion.php";
require "../php/db.php";

if($rol != 'admin')
{
    header("Location: ../../login.php?error=Acceso denegado");
    return;
}

$mensaje = "";

// Agregar materia
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $nombre = $_POST['nombre'];
    $codigo = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];
    $semestre = $_POST['semestre'];
    $profesor_id = $_POST['profesor_id'];
    $grupo = $_POST['grupo'];
    $periodo_academico = $_POST['periodo_academico'];
    $carrera = $_POST['carrera'];
    
    $sql_materia = "INSERT INTO materias (nombre, codigo, descripcion, semestre, carrera) VALUES ('$nombre', '$codigo', '$descripcion', $semestre, '$carrera')";
    
    if(mysqli_query($con, $sql_materia))
    {
        $materia_id = mysqli_insert_id($con);
        
        // Asignar el profesor
        $sql_grupo = "INSERT INTO grupos (profesor_id, materia_id, grupo, semestre, periodo_academico) VALUES ($profesor_id, $materia_id, '$grupo', $semestre, '$periodo_academico')";
        
        if(mysqli_query($con, $sql_grupo))
        {
            $mensaje = "Materia creada";
        } else {
            mysqli_query($con, "DELETE FROM materias WHERE id=$materia_id");
            $mensaje = "Error al asignar profesor";
        }
    } 
    else 
    {
        $mensaje = "Error al crear materia";
    }
}

// Obtener lista de profesores
$sql_profesores = "SELECT id, nombre, apellido_paterno FROM usuarios WHERE rol='profesor' ";
$resultado_profesores = mysqli_query($con, $sql_profesores);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Materias</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo.css">

</head>
<body>

    <nav class="barra">
        <span class="nombre-sistema">Sistema Escolar </span>
        <a href="home.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="inscribir_alumnos.php"><i class="fas fa-user-plus"></i> Inscribir</a>
        <a href="gestionar_materias.php"><i class="fas fa-book"></i> Materias</a>
        <a href="../php/logout.php" class="btn-salir"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </nav>

    <div class="caja">
        <div class="formulario">

            <h2>
                <i class="fas fa-book-open"></i> Agregar Nueva Materia
            </h2>

            <?php if($mensaje): ?>
                <div class="info-mensaje"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="fila-doble">
                    <div class="campo">
                        <label>Nombre de la Materia</label>
                        <input type="text" name="nombre" placeholder="Ejemplo: Programación Web" required>
                    </div>

                    <div class="campo">
                        <label>Código</label>
                        <input type="text" name="codigo" placeholder="Ejemplo: ISC101" required pattern="[A-Z0-9]+">
                    </div>
                </div>

                <div class="campo">
                    <label>Descripción</label>
                    <input name="descripcion" placeholder="Breve descripción de la materia"></input>
                </div>

                <div class="campo">
                    <label>Semestre</label>
                    <select name="semestre" required>
                        <option value="">Selecciona un semestre</option>
                        <option value="1">Semestre 1</option>
                    </select>
                </div>

                <div class="fila-doble">
                    <div class="campo">
                        <label>Profesor *</label>
                        <select name="profesor_id" required>
                            <option value="">Selecciona un profesor</option>
                            <?php while($profesor = mysqli_fetch_array($resultado_profesores)): ?>
                                <option value="<?php echo $profesor['id']; ?>">
                                    <?php echo htmlspecialchars($profesor['nombre'] . " " . $profesor['apellido_paterno']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="campo">
                        <label>Grupo</label>
                        <input type="text" name="grupo" placeholder="A, B, C..." maxlength="1" required>
                    </div>
                </div>

                <div class="campo">
                    <label>Período Académico</label>
                    <select name="periodo_academico" required>
                        <option value="">Selecciona un período</option>
                        <option value="Agosto-Diciembre 2026">Agosto - Diciembre 2026</option>
                    </select>
                </div>

                <div class="campo">
                    <label>Carrera</label>
                    <select name="carrera" required>
                        <option value="">Selecciona una carrera</option>
                        <?php 
                            $sql_carreras = "SELECT nombre FROM carreras";
                            $resultado_carreras = mysqli_query($con, $sql_carreras);
                            while($carrera = mysqli_fetch_array($resultado_carreras)): 
                        ?>
                            <option value="<?php echo $carrera['nombre']; ?>"><?php echo $carrera['nombre']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>


                <button class="btn-guardar" type="submit" style="margin-top: 20px;">
                    <i class="fas fa-save"></i> Guardar Materia
                </button>
                <a href="home.php" class="btn-cancelar">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </form>

        </div>
    </div>

</body>
</html>