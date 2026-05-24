<?php
require "../php/verificar_sesion.php";
require "../php/db.php";

if($rol != 'admin'){
    header("Location: ../../login.php?error=Acceso denegado");
    return;
}

$tipo_usuario = isset($_GET['tipo']) ? $_GET['tipo'] : 'alumno';

// Inscribir ALUMNO
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tipo_form']) && $_POST['tipo_form'] == 'alumno')
{
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $email = $_POST['email'];
    $numero_control = $_POST['numero_control'];
    $carrera = $_POST['carrera'];
    $semestre = $_POST['semestre'];
    $password = $numero_control;

    $sql = "INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, email, numero_control, password, rol, carrera, semestre) VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$email', '$numero_control', '$password', 'alumno', '$carrera', '$semestre')";
    
    if(mysqli_query($con, $sql))
    {
        $alumno_id = mysqli_insert_id($con);
        // Consulto todos los grupos del curso del alumno
        $sql_grupos = "SELECT g.id FROM grupos g 
                       JOIN materias m ON g.materia_id = m.id 
                       WHERE m.carrera = '$carrera' 
                       AND g.semestre = '$semestre'";

        $resultado_grupos = mysqli_query($con, $sql_grupos);

        //lo inscrbo
        while($grupo = mysqli_fetch_array($resultado_grupos))
        {
            $grupo_id = $grupo['id'];
            mysqli_query($con, "INSERT INTO inscripciones (usuario_id, grupo_id) VALUES ($alumno_id, $grupo_id)");
        }

        $mensaje_alumno = "Alumno inscrito y materias asignadas";
    } 
    else 
    {
        $mensaje_alumno = "Error al inscribir";
    }
}

// Inscribir PROFESOR
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tipo_form']) && $_POST['tipo_form'] == 'profesor')
{
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $email = $_POST['email'];
    $numero_control = $_POST['numero_control'];
    $carrera = $_POST['carrera'];
    $password = $numero_control;

    $sql = "INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, email, numero_control, password, rol, carrera) VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$email', '$numero_control', '$password', 'profesor', '$carrera')";
    
    if(mysqli_query($con, $sql))
    {
        $mensaje_profesor = "Profesor inscrito exitosamente";
    } 
    else 
    {
        $mensaje_profesor = "Error al inscribir";
    }
}

//carreras
$sql_carreras = "SELECT id, nombre FROM carreras";
$resultado_carreras = mysqli_query($con, $sql_carreras);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscribir Usuarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        .pestanas {
            display: flex;
            gap: 10px;
            border-bottom: 2px solid white;
        }

        .boton-pestana {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            color: gray;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .boton-pestana.activo {
            color: darkblue;
            border-bottom-color: darkblue;
        }

        .boton-pestana:hover {
            color: darkblue;
        }

        .contenido-pestana {
            display: none;
        }

        .contenido-pestana.activo {
            display: block;
        }
    </style>
</head>
<body>

    <nav class="barra">
        <span class="nombre-sistema">Sistema Escolar</span>
        <a href="home.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="inscribir_alumnos.php"><i class="fas fa-user-plus"></i> Inscribir</a>
        <a href="gestionar_materias.php"><i class="fas fa-book"></i> Materias</a>
        <a href="../php/logout.php" class="btn-salir"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </nav>

    <div class="caja">
        <div class="formulario">

            <h2>Inscribir Usuarios</h2>
            <p class="texto-descripcion">Agrega nuevos alumnos o profesores al sistema.</p>

            <div class="pestanas">

                <button class="boton-pestana <?php echo $tipo_usuario == 'alumno' ? 'activo' : ''; ?>" onclick="cambiarPestana('alumno')">
                    <i class="fas fa-user-graduate"></i> Inscribir Alumno
                </button>

                <button class="boton-pestana <?php echo $tipo_usuario == 'profesor' ? 'activo' : ''; ?>" onclick="cambiarPestana('profesor')">
                    <i class="fas fa-chalkboard-user"></i> Inscribir Profesor
                </button>
            </div>

            <!-- FORMULARIO ALUMNO -->
            <div id="pestana-alumno" class="contenido-pestana <?php echo $tipo_usuario == 'alumno' ? 'activo' : ''; ?>">
                
                <?php if(isset($mensaje_alumno)): ?>
                    <div class="info-mensaje"><?php echo $mensaje_alumno; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="tipo_form" value="alumno">

                    <div class="fila-doble">
                        <div class="campo">
                            <label>Nombre</label>
                            <input type="text" name="nombre" required>
                        </div>
                        <div class="campo">
                            <label>Apellido Paterno</label>
                            <input type="text" name="apellido_paterno" required>
                        </div>
                    </div>

                    <div class="fila-doble">
                        <div class="campo">
                            <label>Apellido Materno</label>
                            <input type="text" name="apellido_materno" required>
                        </div>
                        <div class="campo">
                            <label>Número de Control</label>
                            <input type="text" name="numero_control" required>
                        </div>
                    </div>

                    <div class="campo">
                        <label>Correo Institucional</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="fila-doble">
                        <div class="campo">
                            <label>Carrera</label>
                            <select name="carrera" required>
                                <option value="">Selecciona una carrera</option>
                                <?php 
                                    mysqli_data_seek($resultado_carreras, 0);
                                    while($carrera = mysqli_fetch_array($resultado_carreras)): 
                                ?>
                                    <option value="<?php echo $carrera['nombre']; ?>"><?php echo $carrera['nombre']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="campo">
                            <label>Semestre</label>
                            <select name="semestre" required>
                                <option value="">Selecciona semestre</option>
                                <option value="1">Semestre 1</option>
                            </select>
                        </div>
                    </div>

                    <button class="btn-guardar" type="submit">
                        <i class="fas fa-user-plus"></i> Inscribir Alumno
                    </button>
                </form>
            </div>

            <!-- FORMULARIO PROFESOR -->
            <div id="pestana-profesor" class="contenido-pestana <?php echo $tipo_usuario == 'profesor' ? 'activo' : ''; ?>">
                
                <?php if(isset($mensaje_profesor)): ?>
                    <div class="info-mensaje"><?php echo $mensaje_profesor; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="tipo_form" value="profesor">

                    <div class="fila-doble">
                        <div class="campo">
                            <label>Nombre</label>
                            <input type="text" name="nombre" required>
                        </div>
                        <div class="campo">
                            <label>Apellido Paterno</label>
                            <input type="text" name="apellido_paterno" required>
                        </div>
                    </div>

                    <div class="fila-doble">
                        <div class="campo">
                            <label>Apellido Materno</label>
                            <input type="text" name="apellido_materno" required>
                        </div>
                        <div class="campo">
                            <label>Número de Control / Cédula</label>
                            <input type="text" name="numero_control" required>
                        </div>
                    </div>

                    <div class="campo">
                        <label>Correo Institucional</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="campo">
                        <label>Carrera / Departamento</label>
                        <select name="carrera" required>
                            <option value="">Selecciona carrera</option>
                            <?php 
                                mysqli_data_seek($resultado_carreras, 0);
                                while($carrera = mysqli_fetch_array($resultado_carreras)): 
                            ?>
                                <option value="<?php echo $carrera['nombre']; ?>"><?php echo $carrera['nombre']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <button class="btn-guardar" type="submit">
                        <i class="fas fa-user-plus"></i> Inscribir Profesor
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script>
        function cambiarPestana(tipo) {
            document.querySelectorAll('.contenido-pestana').forEach(el => {
                el.classList.remove('activo');
            });
            document.querySelectorAll('.boton-pestana').forEach(el => {
                el.classList.remove('activo');
            });

            document.getElementById('pestana-' + tipo).classList.add('activo');
            event.target.classList.add('activo');

            window.history.pushState({}, '', '?tipo=' + tipo);
        }
    </script>

</body>
</html>