<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema Escolar</title>
    <link rel="stylesheet" href="css/estilo-login.css">
</head>
<body>

    <div class="container">
        <div class="login-card">
            <div class="login-header">
                <h1>AllOne</h1>
                <p>Ingresa tus datos para entrar</p>
            </div>

            <form action="php/login_procesar.php" method="POST" class="login-form">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Correo o Número de control</label>
                    <input type="text" name="usuario" placeholder="ADMIN001 o tu número de control" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Contraseña</label>
                    <input type="password" name="password" placeholder="Tu contraseña" required>
                </div>

                <button class="btn-login" type="submit">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>

            </form>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>
