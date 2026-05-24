<?php  

    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "sistema_calificaciones";

    $con = mysqli_connect($host, $user, $password, $dbname);

    if (!$con) {
        die("Error de conexión: " . mysqli_connect_error());
    }

?>
